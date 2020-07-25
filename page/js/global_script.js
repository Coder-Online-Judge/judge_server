var url = "page_request.php";
var serverUrl = "";
var runServer = 0;
var serverRunning = 0;

function buildData(keyName,val){
  var data={};
  data[keyName]=val==null?0:val;
  return data;
}

function openPage(pageName){
  $("#dashboardBodyLoader").show();
  $("#dashboardBody").html("");
  $.post(url,buildData(pageName),function(response){
        $("#dashboardBodyLoader").hide();
        $("#dashboardBody").html(response);
  });
}


function loadSubmissionPage(){
  openPage("loadSubmissionPage");
}

function loadGraphPage(){
  openPage("loadGraphPage");
}

function loadJudgeCompiler(){
  openPage("loadJudgeCompiler");
}

function loadOfflineJudge(){
  $("#dashboardBodyLoader").show();
  $("#dashboardBody").html("");
  $.post(url,buildData("loadOfflineJudge"),function(response){
        $("#dashboardBodyLoader").hide();
        $("#dashboardBody").html(response);
        $("#serverUrl").val(serverUrl);
  });
}

function loadSubmissionList(){
  $.post(url,buildData("loadSubmissionList"),function(response){
      $("#submissionList").html(response);
  });
}

function loadJudgeSetting(){
  openPage("loadJudgeSetting");
}

function stopCustomServer(){
  runServer = 0;
}

function startCustomServer(){
  runServer = 1;
  serverUrl = $("#serverUrl").val();
  alert("Started Offline Server");
}

function updateCompiler(e){

  $.get(e.value,{},function(response){
      $("#response").html(response);
  });
}

function runCustomServer(){
  if(runServer == 0 || serverRunning == 1)return;
  if (typeof serverUrl == 'undefined')return;
  serverRunning = 1;
  $("#responseRunServer").html("Loading");
  $.post(url,buildData("runCustomServer",serverUrl),function(response){
       $("#responseRunServer").html(response);
       serverRunning = 0;
  });
}

function loadCompilerList(){
  $.post(url,buildData("loadCompilerList"),function(response){
      $("#compilerList").html(response);
  });
}

setInterval(function(){ 
  if(runServer ==1 ){runCustomServer();}
}, 2000);

setInterval(function(){ 
  loadCompilerList();
}, 2000);


setInterval(function(){ 
  loadSubmissionList();
}, 1500);