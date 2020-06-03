<?php
class Compile {
   
    public $queueData = array();
    public $sendRequestData = array();
    public $sendUrl;
    public $postRequestData;
    public $fixedUrl = "http://judge-online-compiler-git-online-compiler.apps.us-east-2.starter.openshift-online.com/api.php";

    public function __construct(){
        $this->DB=new Database();
        $this->conn=$this->DB->conn;
    }

    public function multipleCompileSubmission($totalProcess=1){

        $this->compileSubmission();
        
        if($totalProcess<=20){
            sleep(1);
            $this->multipleCompileSubmission($totalProcess+1);
        }

        return;
    }

     public function getQueueSubmissionData(){
        $sql = "select * from submissions natural join verdict natural join languages where verdictId=1 limit 1";
        $data = $this->DB->getData($sql);
        if(isset($data[0]))$this->queueData = $data[0];
        return $this->queueData;
    }


    public function compileSubmission($customUrl = ""){
        $this->sendUrl = $customUrl == ""?$this->fixedUrl:$customUrl;
        $this->getQueueSubmissionData();
        $this->processData();
        $this->sendData();

    }

    public function processData(){
        if(empty($this->queueData))return;

        $postRequest = array(
            'sourceCode' => base64_encode($this->queueData['sourceCode']),
            'language' => $this->queueData['languageArgument'],
            'timeLimit' => $this->queueData['timeLimit'],
            'memoryLimit' => $this->queueData['memoryLimit'],
            'input' => base64_encode(file_get_contents("test_case/input/".$this->queueData['token'].".txt")),
            'expectedOutput' => base64_encode(file_get_contents("test_case/output/".$this->queueData['token'].".txt"))
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
            $inputFileName = "test_case/input/".$this->queueData['token'].".txt";
            $outputFileName = "test_case/output/".$this->queueData['token'].".txt";

            if (file_exists($inputFileName))unlink($inputFileName);
            if (file_exists($outputFileName))unlink($outputFileName);

        }
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