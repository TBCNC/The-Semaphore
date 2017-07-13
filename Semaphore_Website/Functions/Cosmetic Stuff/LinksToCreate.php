<?php  
include_once($_SERVER['DOCUMENT_ROOT'].'/Functions/Security/SessionManager.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/Functions/SocialStuff/Inbox.php');
    class LinkCreation{
        public function __construct(){
        }
        private function EchoLink($dir,$text){
            echo "<li><a href='http://".$_SERVER['SERVER_NAME']."/".$dir.""."'>".$text."</a></li>";
        }
        private function CreateNotLogIn(){
            $this->EchoLink("register","Register");
            $this->EchoLink("login","Login");
        }
        private function CreateLoggedIn(){
            $sesMan=new SessionManager();
            $inbox = new Inbox($sesMan->GetCurrentUID());
            $username = $sesMan->GetCurrentUsername();
            echo "<li><a href='http://".$_SERVER['SERVER_NAME']."/profile.php?id=".$sesMan->GetCurrentUID()."'>Hi ".$username."</a></li>";
            $this->EchoLink("competition","Enter race");
            $this->EchoLink("leaderboard","Leaderboard");
            $this->EchoLink("inbox","Messages (".$inbox->GetUnreadAmount().")");
            $this->EchoLink("settings","Settings");
            $this->EchoLink("logout","Log out");
        }
        public function CreateLinks(){
            $sesMan = new SessionManager();
            if($sesMan->IsUserLoggedIn()){
                $this->CreateLoggedIn();
            }else{
                $this->CreateNotLogIn();
            }
        }
    }