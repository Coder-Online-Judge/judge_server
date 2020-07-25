<?php
class Compile {
   
    public $queueData = array();
    public $sendRequestData = array();
    public $sendUrl;
    public $threadId;
    public $postRequestData = array();
    public $inputPath;
    public $outputPath;
    public $isPreviousData;
    public $threadOverleap;
    public $compilerData = array();
    public $IOFileError = 0;
    public $saveSuccess = 0;

    public $IOFileSize = 0;

    public $recursionStartTime;

    public function __construct(){
        $this->DB=new Database();
        $this->conn=$this->DB->conn;
        $this->threadId = uniqid();
    }

    public function multipleCompileSubmission($isStart = true){

        if($isStart)$this->recursionStartTime = strtotime($this->DB->date());
        $now = strtotime($this->DB->date());
        if(($now-$this->recursionStartTime)>=55)return;

        $this->compileSubmission();

        if($this->isPreviousData==0)sleep(1);
        else usleep(100000);
        $this->multipleCompileSubmission(false);

        return;
    }

    public function compileSubmission(){
        
        $this->getCompiler();
        $this->setBusyCompiler();
        $this->checkCompiler();
       // echo $this->sendUrl;

        if($this->sendUrl!=""){

            $this->getQueueSubmissionData();
            $this->setProcessing();
            $this->processData();
            $this->sendData();
            $this->saveCompilerDataTransfer();
            $this->resetProcessing();
        }
        $this->resetCompiler();
        $this->resetCompileSubmission();
        
    }

    public function customCompileSubmission($customUrl=""){
        $this->sendUrl = $customUrl;
        $this->getQueueSubmissionData();
        $this->setProcessing();
        $this->processData();
        $this->sendData();
        $this->resetProcessing();
    }

     public function getQueueSubmissionData(){
        $sql = "select * from submissions natural join verdict natural join languages where verdictId=1 limit 1";
        $data = $this->DB->getData($sql);
        if(isset($data[0]))$this->queueData = $data[0];
        else $this->queueData = array();
        $this->isPreviousData = empty($this->queueData)?0:1;
        return $this->queueData;
    }

    public function getCompiler(){
        $sql = "select * from compiler where compilerBusy=0 and compilerOk=1";
        $data = $this->DB->getData($sql);
        //print_r($data);

        if(isset($data[0])){
            $totalCompiler = count($data);
            $selectCompiler = rand()%$totalCompiler;
            $data = $data[$selectCompiler];
            $this->compilerData = $data;

        }
        else $this->compilerData = array();
        //print_r($this->compilerData); 
    }

    public function setBusyCompiler(){
        if(empty($this->compilerData))return;
        $updateData = array();
        $updateData['compilerId']=$this->compilerData['compilerId'];
        $updateData['compilerBusy']=1;
        $updateData['compilerThreadId']=$this->threadId;
        $this->DB->pushData("compiler","update",$updateData);
    }

    public function checkCompiler(){
        if(empty($this->compilerData))return;
        $compilerId = $this->compilerData['compilerId'];
        $sql = "select compilerThreadId from compiler where compilerId=$compilerId";
        $data = $this->DB->getData($sql);
        $compilerThreadId = $data[0]['compilerThreadId'];
        if($compilerThreadId!=$this->threadId)$this->sendUrl="";
        else $this->sendUrl=$this->compilerData['compilerUrl']."/api.php";
    }

    public function resetCompiler(){
        if(empty($this->compilerData))return;
        $updateData = array();
        $updateData['compilerId']=$this->compilerData['compilerId'];
        $updateData['compilerBusy']=0;
        $updateData['compilerThreadId']="";
        $this->DB->pushData("compiler","update",$updateData);
    }
    
    public function resetCompileSubmission(){
        unset($queueData);
        unset($sendRequestData);
        unset($postRequestData);
        unset($compilerData);
    }

    public function setProcessing(){
        if(empty($this->queueData))return;
        $updateData = array();
        $updateData['submissionId']=$this->queueData['submissionId'];
        $updateData['verdictId']=2;
        $updateData['threadId']=$this->threadId;
        $this->DB->pushData("submissions","update",$updateData);
        
        $submissionId = $this->queueData['submissionId'];
        $sql = "select threadId from submissions where submissionId=$submissionId";
        $data = $this->DB->getData($sql);
        $getThreadId = $data[0]['threadId'];
        $this->threadOverleap = $getThreadId!=$this->threadId;

    }

    public function resetProcessing(){
        if(empty($this->queueData))return;
        if($this->saveSuccess==0){
            $updateData = array();
            $updateData['submissionId']=$this->queueData['submissionId'];
            $updateData['verdictId']=1;
            $this->DB->pushData("submissions","update",$updateData);
        }
        
        $this->saveSuccess = 0;
        $this->IOFileError = 0;
    }

    public function processData(){
        if(empty($this->queueData) || $this->threadOverleap)return;
        $this->inputPath =$this->getTestCasePath("test_case/input/".$this->queueData['token'].".txt");
        $this->outputPath = $this->getTestCasePath("test_case/output/".$this->queueData['token'].".txt");

         if (!file_exists($this->inputPath) || !file_exists($this->outputPath)){
            $this->IOFileError = 1;
            return;
        }

        $this->IOFileSize = filesize($this->inputPath)+filesize($this->outputPath);

        $postRequest = array(
            'sourceCode' => base64_encode($this->queueData['sourceCode']),
            'language' => $this->queueData['languageArgument'],
            'timeLimit' => $this->queueData['timeLimit'],
            'memoryLimit' => $this->queueData['memoryLimit'],
            'input' => base64_encode(file_get_contents($this->inputPath)),
            'expectedOutput' => base64_encode(file_get_contents($this->outputPath))
        );

        $this->postRequestData = $postRequest;
    }

    public function sendData(){
        if(empty($this->queueData)){
            echo "Judge Can Not Have Any Queue Submission";
            return;
        }
        if ($this->threadOverleap) {
            echo "Judge Has Thread Overleap";
            return;
        }
        if($this->IOFileError){
            echo "Input Output File Error";
            return;
        }
        $this->sendSandBox();
    }

    public function sendSandBox(){
        $data = $this->postRequestData;
        $url = $this->sendUrl;
        $this->printCompileData();

        $cURLConnection = curl_init($url);
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $data);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        // $apiResponse - available data from the API request
        $response = json_decode($response,true);
        
        if(!isset($response['status']))return;
            
        $status = $response['status']['status'];
        $statusId = $this->getStatusId($status);
        if($statusId==0)return;

        $updateData = array();
        $updateData['submissionId']=$this->queueData['submissionId'];
        $updateData['verdictId']=$statusId;
        $updateData['time']=$response['time'];
        $updateData['memory']=$response['memory'];

        //compiler response
        // $compilerResponse = array();
        // $compilerResponse['compilerInfo']['conpilerId']=$this->compilerData['compilerId'];
        // $compilerResponse['compilerInfo']['conpilerUrl']=$this->compilerData['conpilerUrl'];
        // $compilerResponse['io']['input']=base64_decode($this->postRequestData['input']);
        // $compilerResponse['io']['expectedOutput']=base64_decode($this->postRequestData['expectedOutput']);
        // $compilerResponse['io']['output']=base64_decode($response['output']);
        // $compilerResponse['status']=$response['status'];
        //$updateData['compilerResponse']=json_encode($compilerResponse);
            
        $this->DB->pushData("submissions","update",$updateData);

        if (file_exists($this->inputPath))unlink($this->inputPath);
        if (file_exists($this->outputPath))unlink($this->outputPath);

        $this->saveSuccess = 1;
    }

    public function saveCompilerDataTransfer(){
        if($this->saveSuccess == 0)return;
        $data = array();
        $data['compilerId'] = $this->compilerData['compilerId'];
        $data['dataSize'] = $this->IOFileSize;
        $data['transferTime'] = $this->DB->date();
        $this->DB->pushData("compiler_data_transfer","insert",$data);
    }
    
    public function getTestCasePath($path){
        $basePath = dirname(__FILE__);

        //problem for cpanel path cronjob need specefic file name otherwise its go to infinate loop
        if (!strpos($basePath, 'wamp64') !== false){
            $basePath = explode("/", $basePath);
            array_pop($basePath);
            $basePath = implode('/', $basePath);
            $path = $basePath . '/' . $path;
        }
        
        return $path;
    }

    public function printCompileData(){
        echo "Url : ".$this->sendUrl."<br/>";
        echo "<pre>";
        print_r($this->postRequestData);
        echo "</pre>";
    }

    public function getStatusId($status){
        $sql="select * from verdict where verdictStatus='$status'";
        $data = $this->DB->getData($sql);
        if(isset($data[0]))return $data[0]['verdictId'];
        return 0;
    }
}
?>