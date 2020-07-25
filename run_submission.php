
<script type="text/javascript" src="style/lib/jquery/jquery.min.js"></script>
<div id="response"></div>
<script type="text/javascript">

	var judgeFinish = 1;

	function runSubmission(){
		if(judgeFinish == 0)return;
		judgeFinish = 0;
		$.get("cron.php",{},function(response){
       		$("#response").html(response);
       		judgeFinish = 1;
    	});
	}

	setInterval(function(){ 
  		runSubmission();
	}, 1500);
	
</script>