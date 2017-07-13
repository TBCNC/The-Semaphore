<?php 
    error_reporting(E_ALL);
    ini_set('display_errors','1');
    include($_SERVER['DOCUMENT_ROOT']."/Functions/Security/InputSecurity.php");
    include($_SERVER['DOCUMENT_ROOT']."/Functions/SQLQuerying/DataCreation.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/POSTPages/Front-end output/ResultForwarding.php");
    $username= filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $password=filter_input(INPUT_POST,"password",FILTER_SANITIZE_STRING);
    $passwordConfirm=filter_input(INPUT_POST,"passwordConfirm",FILTER_SANITIZE_STRING);
    $email=filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL);
    $dateOfBirth = filter_input(INPUT_POST,"dateOfBirth",FILTER_SANITIZE_STRING);
    $country = filter_input(INPUT_POST,"country",FILTER_SANITIZE_STRING);
    $returnMsg="Account has been created successfully! You may now sign in!";
    if(isset($username)&&isset($password)&&isset($passwordConfirm)&&isset($email)){
        if($password==$passwordConfirm){
            $securityInput = new InputSecurity();
            $username=$securityInput->CleanseXSS($username);
            $email=$securityInput->CleanseXSS($email);
            $dataWrite=new DataCreation();
            $ip=$_SERVER["REMOTE_ADDR"];
            $doj = date('Y-m-d H:i:s');
            $dob = date('Y-m-d',strtotime($dateOfBirth));
            try{
            $dataWrite->CreateAccount($username, $password, $email, $ip, $country, $dob,$doj);
            }catch (Exception $e){
                $returnMsg="Account creation failed:".$e->getMessage();
            }
            unset($dataWrite);
        }else{
            $returnMsg="Your passwords do not match.";
        }
    }
    
    $redirector = new ResultForwarding($returnMsg);
    $redirector->RedirectWithResult();        
?>
