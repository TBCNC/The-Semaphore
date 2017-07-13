<?php
    //include('../Security/InputSecurity.php');
    error_reporting(E_ALL);
    ini_set('display_errors','1');
    include_once($_SERVER['DOCUMENT_ROOT']."/Functions/SocialStuff/ProfileDetails.php");
    include_once($_SERVER['DOCUMENT_ROOT']."/Functions/SocialStuff/Message.php");
    class DataVisualization{
        private $DB_HOST;
        private $DB_NAME;
        private $DB_USER_READ_NAME;
        private $DB_USER_READ_PASS;

        private $mysqlConnection;

        public function __construct(){ 
            $filePath = $_SERVER['DOCUMENT_ROOT'].'/../../privateInfo/webReadCreds.ini';
            $fileDeatils = file_get_contents($filePath);
            $credentialSplit = explode(",",$fileDeatils);


                //Defining 
            $this->DB_HOST="127.0.0.1";
            $this->DB_USER_READ_NAME=$credentialSplit[0];
            $this->DB_USER_READ_PASS=$credentialSplit[1];
            $this->DB_NAME="websitetesting";
            $this->mysqlConnection=new mysqli($this->DB_HOST,$this->DB_USER_READ_NAME,$this->DB_USER_READ_PASS,$this->DB_NAME);
        }
        function __destructor(){
            $this->mysqlConnection->close();
            unset($this->mysqlConnection);
        }
        private function PerformSQLRead($cmd,$parameter,$type){
            $preparedStmt=$this->mysqlConnection->prepare($cmd);
            if(!$preparedStmt)
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->bind_param($type,$parameter))
                throw new Exception("Failed to bind parameters.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->execute())
                throw new Exception("Failed to execute SQL command provided.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->bind_result($result))
                throw new Exception("Failed to bind results.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->fetch())
                throw new Exception("Failed to fetch results.<br>".$this->mysqlConnection->error);
            $preparedStmt->close();
            return $result;
        }
        public function IsUsernameTaken($username){
            $resultInt = $this->PerformSQLRead("SELECT count(1) FROM AccountInfo WHERE Account_Username=?", $username, "s");
            if($resultInt==0){return false;}
            else{return true;}
        }
        public function IsEmailTaken($email){
            $resultInt = $this->PerformSQLRead("SELECT count(1) FROM AccountInfo WHERE Account_Email=?", $email, "s");
            if($resultInt==0){return false;}
            else{return true;}
        }
        public function IsUserBanned($uid){
            $resultInt=$this->PerformSQLRead("SELECT count(1) FROM Bans WHERE UID=?", $uid, "i");
            if($resultInt==0)
                return false;
            return true;
        }
        public function GetUsername($userID){
            return $this->PerformSQLRead("SELECT Account_Username FROM AccountInfo WHERE uid=?", $userID, "i");
        }
        public function GetUID_Username($username){
            return $this->PerformSQLRead("SELECT UID FROM AccountInfo WHERE Account_Username=?",$username,"s");
        }
        public function GetPass($userID){
            return $this->PerformSQLRead("SELECT Account_Password FROM AccountInfo WHERE UID=?",$userID,"i");
        }
        public function GetEmail($userID){
            return $this->PerformSQLRead("SELECT Account_Email FROM AccountInfo WHERE UID=?",$userID,"i");
        }
        public function GetJoinDate($userID){
            return $this->PerformSQLRead("SELECT Account_JoinDate FROM AccountInfo WHERE UID=?",$userID,"i");
        }
        public function GetCountry($userID){
            return $this->PerformSQLRead("SELECT Account_Country FROM AccountInfo WHERE UID=?",$userID,"i");
        }
        public function GetDOB($userID){
            return $this->PerformSQLRead("SELECT Account_DOB FROM AccountInfo WHERE UID=?",$userID,"i");
        }
        public function GetProfileDescription($userID){
            return $this->PerformSQLRead("SELECT Profile_Description FROM Profile WHERE UID=?",$userID,"i");
        }
        public function GetProfilePicture($userID){//Keep in mind that this should store the URL of the profile picture
            return $this->PerformSQLRead("SELECT Profile_Picture FROM Profile WHERE UID=?",$userID,"i");
        }
        public function GetUserTID($userID){
            return $this->PerformSQLRead("SELECT Profile_Team FROM Profile WHERE UID=?",$userID,"i");
        }
        public function GetUserTeamName($userID){
            $tid = GetUserTID($userID);
            return $this->PerformSQLRead("SELECT Name FROM TEAMS WHERE TID=?",$tid,"i");
        }
        public function GetUserSpecialRole($userID){
            return $this->PerformSQLRead("SELECT Profile_SpecialTitle FROM Profile WHERE UID=?",$userID,"i");
        }
        public function GetTotalWins($userID){
            return $this->PerformSQLRead("SELECT Rank_Won FROM ranking_solo WHERE UID=?",$userID,"i");
        }
        private function GetUIDSide($userID){
            $allFriends=array();
            $preparedStmt=$this->mysqlConnection->prepare("SELECT FriendID FROM friends WHERE UID=?;");
            if(!$preparedStmt)
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->bind_param("i",$userID))
                throw new Exception("Failed to bind parameters.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->execute())
                throw new Exception("Failed to execute SQL command provided.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->bind_result($result))
                throw new Exception("Failed to bind results.<br>".$this->mysqlConnection->error);
            while($preparedStmt->fetch()){
                $newProfile = new ProfileDetails($result);
                array_push($allFriends,$newProfile);
            }
            $preparedStmt->close();
            return $allFriends;
        }
        public function GetAllFriends($userID){
            $list1 = $this->GetUIDSide($userID);
            return $list1;
        }
        public function GetAllMessages($userID){
            $allMessages=array();
            $preparedStmt=$this->mysqlConnection->prepare("SELECT * FROM private_messages WHERE UID=?");
            if(!$preparedStmt)
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
             if(!$preparedStmt->bind_param("i",$userID))
                throw new Exception("Failed to bind parameters.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->execute())
                throw new Exception("Failed to execute SQL command provided.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->bind_result($uid,$uid_sender,$subject,$message,$date,$read,$mid))
                throw new Exception("Failed to bind results.<br>".$this->mysqlConnection->error);
            while($preparedStmt->fetch()){
                $newMessage = new Message($mid,$uid,$uid_sender,$subject,$message,$date,$read);
                array_push($allMessages,$newMessage);
            }
            return $allMessages;
        }
        public function GetAllScores(){
            $allScores=array();
            $preparedStmt=$this->mysqlConnection->prepare("SELECT * FROM ranking_solo");
            if(!$preparedStmt)
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->execute())
                throw new Exception("Failed to execute SQL command provided.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->bind_result($uid,$won,$score))
                throw new Exception("Failed to bind results.<br>".$this->mysqlConnection->error);
            while($preparedStmt->fetch()){
                array_push($allScores,array($uid,$won,$score));
            }
            return $allScores;
        }
        public function GetAllAnnouncements(){
            $allAnnouncements=array();
            $preparedStmt=$this->mysqlConnection->prepare("SELECT * FROM announcements");
            if(!$preparedStmt)
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->execute())
                throw new Exception("Failed to execute SQL command provided.<br>".$this->mysqlConnection->error);
            if(!$preparedStmt->bind_result($aid,$title,$announcement,$date))
                throw new Exception("Failed to bind results.<br>".$this->mysqlConnection->error);
            while($preparedStmt->fetch()){
                array_push($allAnnouncements,array($aid,$title,$announcement,$date));
            }
            return $allAnnouncements;
        }
        public function GetMessagesUnreadAmount($userID){
            return $this->PerformSQLRead("SELECT count(1) FROM private_messages WHERE UID=? AND Message_Read=0", $userID, "i");
        }
        public function IsUserInTeam($userID){
            if($this->PerformSQLRead("SELECT TID FROM AccountInfo WHERE UID=?",$userID,"i")==NULL){
                return false;
            }return true;
        }
        public function SpecialUser($userID){
            if($this->PerformSQLRead("SELECT Profile_SpecialTitle FROM profile WHERE UID=?",$userID,"i")==NULL){
                return false;
            }return true;
        }
        public function GetCurrentPoints($userID){
            return ($this->PerformSQLRead("SELECT Account_Points FROM accountinfo WHERE UID=?",$userID,"i"));
        }
        public function GetTotalPoints($userID){
            return ($this->PerformSQLRead("SELECT Rank_TotalWinnings FROM ranking_solo WHERE UID=?", $userID, "i"));
        }
        public function GetBanDetails($userID){
            $banEnd = $this->PerformSQLRead("SELECT Ban_End FROM bans WHERE UID=?", $userID, "i");
            $banReason = $this->PerformSQLRead("SELECT Ban_Reason FROM bans WHERE UID=?",$userID,"i");
            return array($banEnd,$banReason);
        }
    }
?>
