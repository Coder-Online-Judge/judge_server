<?php
class API {
   	
	public $createSubmissionData;

    public function __construct(){
        $this->DB=new Database();
        $this->conn=$this->DB->conn;
    }

    public function getSubmissionList($json=0){
    	$sql="select * from languages";
    	$data=$this->DB->getData($sql);
    	print_r($data);
    }

    public function getAllLanguageList($json=0){
    	$sql="select * from languages where isArchived=0";
    	$data=$this->DB->getData($sql);
    	return $json?json_encode($data):$data;
    }

    public function checkLanguageList($languageId){
    	$languageId = (int)$languageId;
    	$sql="select * from languages where languageId=$languageId and isArchived=0";
    	$data=$this->DB->getData($sql);
    	return isset($data[0])?1:0;
    }

    public function getSubmission($token,$json=0){
    	$sql="select * from submissions natural join verdict where token='$token'";
    	$data=$this->DB->getData($sql);
    	$returnData = array();
    	if(!isset($data[0]))$returnData['error']="Token Is Not Found";
    	else{
    		$data = $data[0];
    	
    		$returnData['time'] = $data['time'];
    		$returnData['memory'] = $data['memory'];
    		$returnData['token'] = $data['token'];
    		$returnData['status']['id'] = $data['verdictId'];
    		$returnData['status']['description'] = $data['verdictDescription'];
    	}
    	return $json?json_encode($returnData):$returnData;
    }

    public function getRandomString($n) { 
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyz'; 
    	$randomString = ''; 
  
    	for ($i = 0; $i < $n; $i++) { 
        	$index = rand(0, strlen($characters) - 1); 
        	$randomString .= $characters[$index]; 
    	} 
  
    	return $randomString; 
	} 

    public function makeSubmissionToken($submissionId){
    	$submissionHash = md5($submissionId);
    	$randomString = $this->getRandomString(15);
    	$rendomHash = uniqid();
    	$token="$randomString-$submissionHash-$rendomHash";
    	return $token;
    }

    public function createFile($url, $file_name, $txt){
        $new_file_name = $url . $file_name;
        $file = fopen($new_file_name, "w");
        fwrite($file, $txt);
        fclose($file);
    }

    public function createSubmission($apiData,$json=0){
    	$this->createSubmissionData = $apiData;
    	$data=array();
    	$data['sourceCode'] =isset($apiData['sourceCode'])?$apiData['sourceCode']:"";
    	$data['languageId'] =isset($apiData['languageId'])?$apiData['languageId']:"";
    	$data['timeLimit'] =(int)isset($apiData['timeLimit'])?$apiData['timeLimit']:2;
    	$data['memoryLimit'] =(int)isset($apiData['memoryLimit'])?$apiData['memoryLimit']:128000;
    	$data['date'] =$this->DB->date();

    	$error = array();
    	if($data['sourceCode']=="")$error['error']="source code can't be blank";
    	else if(!$this->checkLanguageList($data['languageId']))$error['error']="language id not valid";

    	if(isset($error['error']))return json_encode($error);

  		$data['sourceCode']=$this->DB->buildSqlString(base64_decode($data['sourceCode']));

    	$response = $this->DB->pushData("submissions","insert",$data);
    	
    	if($response['error']==0){
    		$insertId = $response['insert_id'];
    		$token = $this->makeSubmissionToken($insertId);
    		$dataUpdate = array();
    		$dataUpdate['submissionId'] = $insertId;
    		$dataUpdate['token'] = $token;
    		$this->DB->pushData("submissions","update",$dataUpdate);

    		$input = isset($apiData['input'])?$apiData['input']:"";
    		$expectedOutput = isset($apiData['expectedOutput'])?$apiData['expectedOutput']:"";
    		$input = base64_decode($input);
    		$expectedOutput = base64_decode($expectedOutput);
    		$file_name = $token.".txt";

    		$this->createFile("test_case/input/",$file_name,$input);
 			$this->createFile("test_case/output/",$file_name,$expectedOutput);
 			$returnData = array();
 			$returnData['token'] = $token;
 			return json_encode($returnData);
    	}

    	$error['error'] ="your submission is not created";

    	return json_encode($error);
    }

 
}
?>