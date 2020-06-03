<?php 
	$response = $JudgeScript->getJudgeLanguageList();
	
	echo "<pre><h1>Language List</h1>";
	print_r($response);
	echo "</pre>";

	$response = $JudgeScript->getJudgeVerdictList();
	
	echo "<pre><h1>Verdict List</h1>";
	print_r($response);
	echo "</pre>";

?>