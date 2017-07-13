<?php
/*
 * Important things to add:
 * -Add some kind of 2FA by checking the IP of the current user.
 * -Protect against session hijacking and php session hijacking, make sure to regenerate the session ID.
 * -Worry about these things later however
 */
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors','1');
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/Functions/Security/InputSecurity.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Functions/SQLQuerying/DataVisualization.php');
    
    class SessionManager{
        private $userName;
        private $password;
        //Figure out way to do overloading on this
        function __construct(){
        }
        public static function MainConstructor($userName,$password){
            $self = new self();
            $self->userName=$userName;
            $self->password=$password;
            return $self;
        }
        private function ConfirmLogin(){
            $security = new InputSecurity();
            $sqlReading = new DataVisualization();
            
            $userID = $sqlReading->GetUID_Username($this->userName);
            if($userID!=0){
                $userPass = $sqlReading->GetPass($userID);

                if($security->CorrectPass($userPass, $this->password)){
                    return true;
                }
            }
            return false;
            
        }
        private function SetSessionVariables_Login(){
            $sqlRead = new DataVisualization();
            $_SESSION["UID"]=$sqlRead->GetUID_Username($this->userName);
            $_SESSION["username"]=$this->userName;
            unset($sqlRead);
        }
        private function SetSessionVariables_Logout(){
            $_SESSION["UID"]=NULL;
            $_SESSION["username"]=NULL;
        }
	public function GetCurrentUsername(){
            return $_SESSION["username"];
	}
        public function GetCurrentUID(){
            return $_SESSION["UID"];
        }
        public function IsUserLoggedIn(){
            if(isset($_SESSION["username"]))
                return true;
            else
                return false;
        }
        public function Login(){
            if($this->ConfirmLogin()){
                $this->SetSessionVariables_Login();
                return true;
            }else{
                return false;
            }
        }
        public function Logout(){
            if($this->IsUserLoggedIn()){
                $this->SetSessionVariables_Logout();
                return true;
            }
            return false;
        }
        public function GetIP(){
            return $_SERVER["REMOTE_ADDR"];
        }
    }
        
 
    /*
    $sessionMan = new SessionManager("NewUser","stupidPass2*");
    if($sessionMan->IsUserLoggedIn()==FALSE){
        echo "User is not logged in! Logging in.<br>";
        if($sessionMan->Login()){
            echo "Logged in successfully!<br>";
        }else{
            echo "Incorrect password!<br>";
        }
    }else{
        echo "User is already logged in!<br>";
    }
    */
?>
