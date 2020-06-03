<?php
	include "script.php";

	if(isset($_POST['loadSubmissionPage'])){
		include "page/submission_table.php";
	}
	else if(isset($_POST['loadGraphPage'])){
		include "page/dashboard_graph.php";
		include "page/graph_script.php";
	}
	else if(isset($_POST['loadOfflineJudge'])){
		include "page/offline_judge.php";
	}

	else if(isset($_POST['loadJudgeSetting'])){
		include "page/judge_setting.php";
	}
	else if(isset($_POST['runCustomServer'])){
		$serverUrl = $_POST['runCustomServer'];
		$data = $Compile->compileSubmission($serverUrl);
	}


?>
