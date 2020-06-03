<?php
class JudgeScript {
   
    public function __construct(){
        $this->DB=new Database();
        $this->conn=$this->DB->conn;
    }

    public function getSubmissionList($json=0){
    	$sql="select * from submissions natural join verdict natural join languages order by submissionId desc";
    	$data=$this->DB->getData($sql);
    	return $json?json_encode($data):$data;
    }

    public function getCountSubmissionVerdict($json=0){
    	$sql="select verdictId,verdictStatus,verdictDescription,(SELECT count(*) as total from submissions WHERE submissions.verdictId=verdict.verdictId) as total from verdict";
    	$data=$this->DB->getData($sql);
    	return $json?json_encode($data):$data;
    }

    public function getCountSubmissionLanguage($json=0){
    	$sql="select languageId,languageName,languageArgument,(SELECT count(*) as total from submissions WHERE submissions.languageId=languages.languageId) as total from languages";
    	$data=$this->DB->getData($sql);
    	return $json?json_encode($data):$data;
    }

    public function getJudgeLanguageList($json=0){
    	$sql="select * from languages";
    	$data=$this->DB->getData($sql);
    	return $json?json_encode($data):$data;
    }

    public function getJudgeVerdictList($json=0){
    	$sql="select * from verdict";
    	$data=$this->DB->getData($sql);
    	return $json?json_encode($data):$data;
    }





 
}
?>