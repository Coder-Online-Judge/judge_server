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
		$data = $Compile->customCompileSubmission($serverUrl);
	}

	else if(isset($_POST['loadSubmissionList'])){
		include "page/submission_list.php";
	}

	else if(isset($_POST['loadJudgeCompiler'])){
		include "page/compiler/judge_compiler_list.php";
	}
	else if(isset($_POST['loadCompilerList'])){
		include "page/compiler/data_transfer_graph.php";
	}


?>
