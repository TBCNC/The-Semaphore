<?php

$userName = $_POST['username'];
$password = $_POST['password'];

include_once($_SERVER['DOCUMENT_ROOT'].'/Functions/Security/SessionManager.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/Functions/SQLQuerying/DataVisualization.php');
include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/POSTPages/Front-end output/ResultForwarding.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataCreation.php");

$result="";

if(isset($userName)&&isset($password)){
    $security = SessionManager::MainConstructor($userName, $password);
    $ipAddress = $security->GetIP();
    if($security->Login()){
        //Check if user is banned
        $dataVis = new DataVisualization();
        $uid = $dataVis->GetUID_Username($userName);
        if($dataVis->IsUserBanned($uid)){
            //Let's check if the ban expired.
            echo "User is banned.";
            $banDetails = $dataVis->GetBanDetails($uid);
            $dateFinished = $banDetails[0];
            $banReason = $banDetails[1];
            $currentDate = date('Y-m-d H:i:s');
            if($currentDate > $dateFinished){
                //Unban user and sign them in
                $dataCreation = new DataCreation();
                $dataCreation->UnbanAccount($uid);
                header('Location:../../../UserHomepage/');
            }else{
                //User is banned
                $security->Logout();
                $result="You are currently banned from the website for the following reason: <br> {$banReason} <br><br> Your ban expires on {$dateFinished}.";
            }
        }else{  
            header('Location:../../../UserHomepage/');
        }
        unset($dataVis);
    }else{
        $result="Your username or password is incorrect. Please try again.";
    }
}else{
    header('Location:../../index.php');
}
$redirector = new ResultForwarding($result);
$redirector->RedirectWithResult();
?>
