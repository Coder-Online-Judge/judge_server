<style type="text/css">
	body{
		background: #F1F1F1;
		overflow-x: hidden;
		font-family: "Exo 2";
	}
	.topTitleArea{
		text-align: center;
		background-image: url("upload_file/top-bg-image.png");
		background-size: cover;
		min-height: 220px;
		padding-top: 5px;
		width: 100%;
  		
	}
	.topTitle{
		font-size: 6em;
		font-weight: bold;
		color: #ffffff;
		margin-bottom: -30px;
	}
	.topSubTitle{
		font-size: 2em;
		color: #ecf0f1;
		font-weight: bold;
	}
	.btnArea{
		padding: 4px;
	}
	.btnServer{
		text-decoration: none;
        margin: 0.4em 0.2em 0.6em 0.2em;
        border-radius: 5px;
        padding: 0.75em 2em;
        color: #ffffff;
        font-weight: bold;
        font-size: 14px;
        border: 1px solid rgba(255, 255, 255, 0.5);

	}
	.btnServer:hover{
		outline: none;
		background-color: rgba(255,255,255,0);
		color: #ffffff;
	}
	.btnServer:focus{
		outline: none!important;
		color: #ffffff;
	}

	.btnColor1{
		background-color: rgba(66, 133, 244, 1);
	}
	.btnColor2{
		background-color: rgba(219, 68, 55, 1);
	}
	.btnColor3{
		background-color: rgba(244, 180, 0, 1);
	}
	.btnColor4{
		background-color: rgba(15, 157, 88, 1);
	}


</style>

<div class="topTitleArea">
	<div class="topTitle">CoderOJ</div>
	<div class="topSubTitle">Judge Server</div>
	<div class="btnArea">
		<button class="btn btnServer btnColor1" onclick="loadGraphPage()">Judge Status Graph</button>
		<button class="btn btnServer btnColor2" onclick="loadSubmissionPage()">Submission List</button>
		<button class="btn btnServer btnColor3" onclick="loadJudgeSetting()">Judge Info</button>
		<button class="btn btnServer btnColor4" onclick="loadOfflineJudge()">Custom Judge</button>
	</div>
</div>