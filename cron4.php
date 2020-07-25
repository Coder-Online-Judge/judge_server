<?php

	include "config/db.php";
	include "config/connect.php";
	include "script/compile.php";
	sleep(2);
	$Compile = new Compile();
    $Compile->multipleCompileSubmission();
//	echo "working fine";

?>