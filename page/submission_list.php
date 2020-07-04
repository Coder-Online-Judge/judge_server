<table class="table" style="width: 100%;overflow-x: scroll;">
	<tr>
		<td class="td1">#</td>
		<td class="td1">Language</td>
		<td class="td1">Date</td>
		<td class="td1">Time</td>
		<td class="td1">Memory</td>
		<td class="td1">Verdict</td>
		
	</tr>
	<?php 
	$submissionList = $JudgeScript->getSubmissionList();
	$c=0;
	foreach ($submissionList as $key => $value) {
		$c++;
		$verdict = $value['verdictStatus'];
		$verdictClass = "";
		if($c==50)break;
	?>
		<tr>
			<td class="td2"><?php echo $value['submissionId']; ?></td>
			<td class="td2 "><label class="label languageName"><?php echo $value['languageName']; ?></label></td>
			<td class="td2"><?php echo $DB->dateToString($value['date']); ?></td>
			<td class="td2"><?php echo $value['time']; ?></td>
			<td class="td2"><?php echo $value['memory']; ?></td>
			<td class="td2"><label class="label <?php echo $value['verdictStatus']; ?>"><?php echo $value['verdictDescription']; ?></label></td>
		</tr>

	<?php } ?>
</table>