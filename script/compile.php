<?php
class Compile {
   
    public $queueData = array();
    public $sendRequestData = array();
    public $sendUrl;
    public $postRequestData;
    public $fixedUrl = "http://compiler-online-compiler.apps.us-east-2.starter.openshift-online.com/api.php";
    public $inputPath;
    public $outputPath;
    public $isPreviousData;

    public $recursionStartTime;

    public function __construct(){
        $this->DB=new Database();
        $this->conn=$this->DB->conn;
    }

    public function multipleCompileSubmission($isStart = true){

        if($isStart)$this->recursionStartTime = strtotime($this->DB->date());
        $now = strtotime($this->DB->date());
        if(($now-$this->recursionStartTime)>=55)return;

        $this->compileSubmission();

        if($this->isPreviousData==0)sleep(1);
        $this->multipleCompileSubmission(false);

        return;
    }

     public function getQueueSubmissionData(){
        $sql = "select * from submissions natural join verdict natural join languages where verdictId=1 limit 1";
        $data = $this->DB->getData($sql);
        if(isset($data[0]))$this->queueData = $data[0];
        else $this->queueData = array();
        $this->isPreviousData = empty($this->queueData)?0:1;
        return $this->queueData;
    }


    public function compileSubmission($customUrl = ""){
        $this->sendUrl = $customUrl == ""?$this->fixedUrl:$customUrl;
        $this->getQueueSubmissionData();
        $this->setProcessing();
        $this->processData();
        $this->sendData();

    }

    public function setProcessing(){
        if(empty($this->queueData))return;
        $updateData = array();
        $updateData['submissionId']=$this->queueData['submissionId'];
        $updateData['verdictId']=2;
        $this->DB->pushData("submissions","update",$updateData);
    }

    public function processData(){
        if(empty($this->queueData))return;
        $this->inputPath = $this->getTestCasePath("test_case/input/".$this->queueData['token'].".txt");
        $this->outputPath = $this->getTestCasePath("test_case/output/".$this->queueData['token'].".txt");

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
        print_r($response);
        print_r($this->queueData);
       // echo "<textarea>".print_r($data)."</textarea>";
        if(isset($response['status'])){
            $status = $response['status']['status'];
            $statusId = $this->getStatusId($status);
            if($statusId==0)return;
            $updateData = array();
            $updateData['submissionId']=$this->queueData['submissionId'];
            $updateData['verdictId']=$statusId;
            $updateData['time']=$response['time'];
            $updateData['memory']=$response['memory'];
            $this->DB->pushData("submissions","update",$updateData);
            
            if (file_exists($inputFileName))unlink($this->inputPath);
            if (file_exists($outputFileName))unlink($this->outputPath);

        }
        else{
            $updateData = array();
            $updateData['submissionId']=$this->queueData['submissionId'];
            $updateData['verdictId']=1;
            $this->DB->pushData("submissions","update",$updateData);
        }
    }
    
    public function getTestCasePath($path){
        $basePath = dirname(__FILE__);

        //problem for cpanel path cronjob need specefic file name otherwise its go to infinate loop
        if (!strpos($basePath, 'wamp64') !== false)
        {
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