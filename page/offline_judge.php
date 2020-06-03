<style type="text/css">
	.offlineJudgeArea{
		text-align: center;
	}
	.oflineInput{
		margin-bottom: 10px;
		padding: 10px;
		font-size: 18px;
		border-radius: 5px;
		border: 1px solid #aaaaaa;
	}
	.responseRunServer{
		margin-top: 15px;
		text-align: left;
	}
</style>
<script type="text/javascript" src="page/js/run_server.js"></script>
<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="offlineJudgeArea">
			<input type="text" name="" id="serverUrl" class="oflineInput" placeholder="Enter Custom Judge URL"><br/>
			<button class="btn" style="font-size: 17px;" onclick="startCustomServer()">Run Server</button>
			<button class="btn" style="font-size: 17px;" onclick="stopCustomServer()">Stop Server</button>
			<div class="responseRunServer" id="responseRunServer"></div>
		</div>
	</div>
</div>