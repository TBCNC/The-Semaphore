<?php
    class InputSecurity{
        function CleanseXSS($phrase){
            return htmlspecialchars($phrase);
        }    
        function HashPassword($password){
            return password_hash($password, PASSWORD_BCRYPT, ['cost'=>12]);
        }
        function CorrectPass($hash,$pass){
            return password_verify($pass, $hash);
        }
        function GenerateVerificationCode(){
            $characters = "012345689abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $code='';
            for($c=0;$c<8;$c++){
                $code.=$characters[mt_rand(0,strlen($characters)-1)];
            }
            return $code;
        }
    }
    /*
    $security = new InputSecurity();
    $testHash=$security->HashPassword("stupidPass2*");
    echo strlen($testHash);*/
    /*$security = new InputSecurity();
    $hashTest = $security->HashPassword("123456");
    if(password_verify("123",$hashTest)){
        echo "This is the password!";
    }else{
        echo "This is not the password!";
    }
    */
?>