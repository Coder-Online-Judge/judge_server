<?php
	header('Access-Control-Allow-Origin: *');
	include "config/config.php";
	include "script/api.php";
	$API = new API();

	$response = "";

	if(isset($_POST['createSubmission'])){
		
		$response = $API->createSubmission(json_decode($_POST['createSubmission'],true),true);
	}

	else if(isset($_GET['token'])){
		$response = $API->getSubmission($_GET['token'],true);
	}

	else if(isset($_GET['language'])){
		$response = $API->getAllLanguageList(1);
	}
	else{
		$data = array();
		$data['error']="request is not valid";
		$response = json_encode($data);
	}

	echo "$response";


	

?>