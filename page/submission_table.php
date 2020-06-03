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
	tr{
		border: 1px solid #DDDDDD;
	}
	.vardict{

	}
	.AC{
		background-color: #5CB85C;
		color: #ffffff;
	}
	.WA{
		background-color: #DA534F;
		color: #ffffff;
	}
	.TLE{
		background-color: #3879B6;
		color: #ffffff;
	}
	.RTE{
		background-color: #8e44ad;
		color: #ffffff;
	}
	.CE{
		background-color: #8B1B0F;
		color: #ffffff;
	}
	.QUEUE{
		background-color: #777777;
		color: #ffffff;
	}
	.PROCESS{
		background-color: #22a6b3;
		color: #ffffff;
	}
	.MLE{
		background-color: #3c6382;
		color: #ffffff;
	}
	.EFE{
		background-color: #f0932b;
		color: #ffffff;
	}
	.OLE{
		background-color: #38ada9;
		color: #ffffff;
	}

	.IE{
		background-color: #ea8685;
		color: #ffffff;
	}
	.languageName{
		color: #2c3e50;
		font-weight: bold;
		padding: 5px;
		font-size: 16px;

	}
	.submissionFilter{
		text-align: center;
		padding-bottom: 10px;
	}
	.filter_td1{
		background-color: #eeeeee;
		padding: 6px 10px 6px 10px;
		border: 1px solid #ffffff;
		text-align: center;
	}
	.filter_td2{
		padding: 6px 10px 6px 10px;
		border: 1px solid #f5f5f5;
		text-align: center;
	}
	.languageIcon{
		height: 10px;
		width: 10px;
	}
	.submissionCard{
		margin-bottom: 10px;
		height: 100px;
		font-weight: bold;
	}
	.submissionCardVal{
		text-align: center;
		font-size: 30px;
		font-weight: bold;
	}
</style>

<div class="row">
	<div class="col-md-4">
		<div class="row">
			<?php
				$verdictCount = $JudgeScript->getCountSubmissionVerdict();
				foreach ($verdictCount as $key => $value) {

			?>

			<div class="col-md-6">
				<div class="boxBody submissionCard <?php echo $value['verdictStatus']; ?>">
					<?php echo $value['verdictDescription']; ?>
					<div class="submissionCardVal"><?php echo $value['total']; ?></div>
				</div>
			</div>
			<?php } ?>

		</div>
	</div>

<div class="col-md-8">


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

</div>
</div>