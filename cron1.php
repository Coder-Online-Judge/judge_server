<?php

	include "config/db.php";
	include "config/connect.php";
	include "script/compile.php";
	$data = $Compile->multipleCompileSubmission();
//	echo "working fine";

?>