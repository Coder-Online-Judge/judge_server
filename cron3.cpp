<?php

	include "config/db.php";
	include "config/connect.php";
	include "script/compile.php";
	sleep(2);
	$data = $Compile->multipleCompileSubmission();
//	echo "working fine";

?>