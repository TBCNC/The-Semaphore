<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataVisualization.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataCreation.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/Security/SessionManager.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/Security/InputSecurity.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/POSTPages/Front-end output/ResultForwarding.php");

$sessionManager=new SessionManager();
if(!$sessionManager->IsUserLoggedIn()){
    header('location:../../../index.php');
}
$senderUID = $sessionManager->GetCurrentUID();

$finalMsg = "Message has been sent successfully!";

$receiever_username=$_POST["receiver"];
$msg_subject=$_POST["subject"];
$msg_content=$_POST["message"];
if(isset($receiever_username)&&isset($msg_subject)&&isset($msg_content)){
    $dataVis=new DataVisualization();
    //Let's check whether the username exists first.
    if($dataVis->IsUsernameTaken($receiever_username)){
        $dataCre=new DataCreation();
        $currentDate = date("Y-m-d H:i:s");
        $receiverID=$dataVis->GetUID_Username($receiever_username);
        $inputSecurity = new InputSecurity();
        $msg = new Message(0,$receiverID,$senderUID,$inputSecurity->CleanseXSS($msg_subject),$inputSecurity->CleanseXSS($msg_content),$currentDate,0);
        $dataCre->SendMessage($msg,false);
        unset($dataCre);
    }else{
        $finalMsg="The username you are trying to send a message to does not exist!";
    }
}else{
    header('location:../../../index.php');
}
$redirector = new ResultForwarding($finalMsg);
$redirector->RedirectWithResult();
?>
