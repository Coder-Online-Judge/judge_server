<style type="text/css">
	.dashboardBody{
		background: #ffffff;
		height: auto;
		min-height: 500px;
		padding: 15px;
		margin-top: 5px;
	}
	.boxHeader{
	background: linear-gradient(to bottom right, #ffffff, #eeeeee);
	color: #2F353B;
	padding: 10px 10px 10px 10px;
	font-weight: bold;
	font-size: 14px;
	border-radius: 5px 5px 0px 0px;
	margin-bottom: 1px;
	border: 1px solid  #E7ECF1;
	font-family: "Comic Sans MS", cursive, sans-serif;
}

.boxBody{
	background-color: #ffffff;
	border: 1px solid #E7ECF1;
	padding: 10px;
	border-radius: 5px;
}


.box{
	margin-bottom: 20px;
	/*box-shadow: 2px 2px 2px 2px #aaaaaa;*/
	border-radius: 5px;
    box-shadow: 0 0 5px 3px #aaaaaa;
}

.graphBox{
	width: 100%; height: 400px;display: inline-block;
	margin-bottom: 10px;
}

.dashboardBodyLoader{
	text-align: center;
	display: none;
}

.loader{
	height: 200px;
	width: 350px;
}

</style>

<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<div id='response'></div>
<div class="row dashboardBody">
	<div class="dashboardBodyLoader" id="dashboardBodyLoader"><img class="loader" src="https://cdn.shopify.com/s/files/1/0382/4185/files/loading.gif?100"></div>
	<div id="dashboardBody"><?php include "page/dashboard_graph.php"; ?></div>
	
</div>

<?php include "page/graph_script.php"; ?>