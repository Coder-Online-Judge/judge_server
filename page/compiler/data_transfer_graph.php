<style type="text/css">
	.td1{
		background-color: #F4F6F8;
		font-weight: bold;
		
		padding: 10px;
		text-align: center;
	}
	.td2{
		background-color: #ffffff;
		text-align: center;
		
		padding: 15px;
	}

	.success{
		color: green;
		font-size: 18px;
	}
	.failed{
		color: red;
		font-size: 18px;
	}
	.busy{
		color: #F79647;
		font-size: 18px;
	}
</style>

<table style="width: 100%">
	<tr>
		<td class="td1">#</td>
		<td class="td1">Compiler Url</td>
		<td class="td1">Compiler Ok</td>
		<td class="td1">Compiler Busy</td>
		<td class="td1">Compiler Last Test</td>
		<td class="td1">Compiler Added Date</td>
		<td class="td1">Total Data Transfer</td>
		<td class="td1">Total Judge</td>
		<td class="td1"></td>
	</tr>
	<?php 
		$compilerInfo=$JudgeScript->getJudgeCompilerList();
		foreach ($compilerInfo as $key => $value) {
			$compilerUrl = $value['compilerUrl'];
			$compilerName = $value['compilerName'];
			$compilerOk = $value['compilerOk'];
			$compilerOk = $compilerOk?"<i class='fa fa-check-circle success' title='OK'></i>":"<i class='fa failed fa-times-circle-o'></i>";
			$compilerBusy = $value['compilerBusy']==0?"<i class='fa fa-check-circle success' title='OK'></i>":"<i class='fa fa-clock-o busy'></i>";
			$compilerAdded = $value['serverAddedDate'];
			$compilerLastTest = $value['compilerTestDate'];
			$totalDataTransfer = $value['totalDataTransfer'];
			$totalDataTransferMethod = "bytes";
			if($totalDataTransfer > 1000 && $totalDataTransfer<1000000){
				$totalDataTransfer = $totalDataTransfer/1000;
				$totalDataTransferMethod = "kb";
			}
			else if($totalDataTransfer>1000000){
				$totalDataTransfer = $totalDataTransfer/1000000;
				$totalDataTransferMethod = "mb";
			}
			$totalDataTransfer = sprintf('%0.1f', $totalDataTransfer);
		
	?>
		<tr>
			<td class="td2"><?php echo $value['compilerId']; ?></td>
			<td class="td2"><a href="<?php echo $compilerUrl; ?>" target="_blank"><?php echo $compilerUrl; ?></a></td>
			<td class="td2"><?php echo $compilerOk; ?></td>
			<td class="td2"><?php echo $compilerBusy; ?></td>
			<td class="td2"><?php echo $compilerLastTest; ?></td>
			<td class="td2"><?php echo $compilerAdded; ?></td>
			<td class="td2"><?php echo $totalDataTransfer; ?> <?php echo $totalDataTransferMethod; ?></td>
			<td class="td2"><?php echo $value['totalJudge']; ?></td>
			<td class="td2"><button class="btn" value='<?php echo "$compilerUrl"."update.php"; ?>' onclick="updateCompiler(this)">Update</button></td>
		</tr>

	<?php } ?>
</table>

<div id="response"></div>
