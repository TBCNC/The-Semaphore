<?php
    error_reporting(E_ALL);
    ini_set('display_errors','1');
    include_once($_SERVER['DOCUMENT_ROOT']."/Functions/SQLQuerying/DataVisualization.php");
    include_once($_SERVER['DOCUMENT_ROOT']."/Functions/SocialStuff/Message.php");
    class DataCreation{
        private $DB_HOST;
        private $DB_NAME;
        private $DB_USER_WRITE_NAME;
        private $DB_USER_WRITE_PASS;

        private $mysqlConnection;

        public function __construct(){ 
            $filePath = $_SERVER['DOCUMENT_ROOT'].'/../../privateInfo/webWriteCreds.ini';
            $fileDeatils = file_get_contents($filePath);
            $credentialSplit = explode(",",$fileDeatils);


            //Defining 
            $this->DB_HOST="127.0.0.1";
            $this->DB_USER_WRITE_NAME=$credentialSplit[0];
            $this->DB_USER_WRITE_PASS=$credentialSplit[1];
            $this->DB_NAME="websitetesting";
            $this->mysqlConnection=new mysqli($this->DB_HOST,$this->DB_USER_WRITE_NAME,$this->DB_USER_WRITE_PASS,$this->DB_NAME);
        }
        function __destructor(){
            $this->mysqlConnection->close();
            unset($this->mysqlConnection);
        }
        private function SetupUser(){
            $tableNames = array("Profile","Community","Ranking_Solo");
            foreach($tableNames as $table){
                $sqlCMD = "INSERT INTO ".$table." VALUES ()";
                $stmt = $this->mysqlConnection->prepare($sqlCMD);
                if($stmt==false){
                    die("Prepare failed:\n".htmlspecialchars($this->mysqlConnection->error));
                }
                if($stmt->execute()==false){
                    die("Execution failed:\n".htmlspecialchars($this->mysqlConnection->error));
                }
                $stmt->close();
            }
        }
        public function CreateAccount($username,$password,$email,$ip,$country,$dob,$joinDate){
            $security = new InputSecurity();
            $sqlReading = new DataVisualization();
            $sqlCmd = "INSERT INTO AccountInfo (Account_Username,Account_Password,Account_Email,Account_IPAddresses,Account_Country,Account_DOB,Account_JoinDate) VALUES (?,?,?,?,?,?,?)";
            if($sqlReading->IsUsernameTaken($username)==false){
                if($sqlReading->IsEmailTaken($email)==false){
                    $preparedStmt = $this->mysqlConnection->prepare($sqlCmd);
                    if($preparedStmt==false){
                        die("Prepare failed:\n".htmlspecialchars($this->mysqlConnection->error));
                    }
                    $this->SetupUser();
                    $preparedStmt->bind_param("sssssss",$security->CleanseXSS($username),$security->HashPassword($password),$security->CleanseXSS($email),$security->CleanseXSS($ip),$security->CleanseXSS($country),$security->CleanseXSS($dob),$joinDate);
                    $preparedStmt->execute();
                    $preparedStmt->close();
                    
                }else{
                    throw new Exception('Email already in use');
                }
            }else{
                throw new Exception('Username already in use');
            }
        }
        public function BanAccount($uid,$length,$reason){
            $dateEnd = date('Y-m-d H:i:s', strtotime($dateStart.' + '.$length.' days'));
            $sqlCommand = "INSERT INTO Bans (UID,Ban_End,Ban_Reason) VALUES(?,?,?)";
            $stmt = $this->mysqlConnection->prepare($sqlCommand);
            if(!$stmt)
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            if(!$stmt->bind_param("iss",$uid,htmlspecialchars($dateEnd),htmlspecialchars($reason)))
                throw new Exception("Failed to bind paramaters.<br>".$this->mysqlConnection->error);
            if(!$stmt->execute())
                throw new Exception("Unable to execute SQL command.<br>".$this->mysqlConnection->error);
            $stmt->close();
        }
        public function UnbanAccount($uid){
            $sqlCommand = "DELETE FROM bans WHERE UID=?";
            $stmt = $this->mysqlConnection->prepare($sqlCommand);
            if(!$stmt)
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            if(!$stmt->bind_param("i",$uid))
                throw new Exception("Failed to bind paramaters.<br>".$this->mysqlConnection->error);
            if(!$stmt->execute())
                throw new Exception("Unable to execute SQL command.<br>".$this->mysqlConnection->error);
            $stmt->close();
        }
        public function SendMessage($msg,$frq){
            $receiver=$msg->msg_reciever;
            $sender=$msg->msg_sender;
            if(!$frq){
                $subject=htmlspecialchars($msg->msg_subject);
                $content=htmlspecialchars($msg->msg_content);
            }else{
                $subject=$msg->msg_subject;
                $content=$msg->msg_content;
            }
            $dateSent = $msg->msg_date;
            $sqlCommand = "INSERT INTO private_messages (UID,UID_Sender,Subject,Message,DateSent) VALUES(?,?,?,?,?)";
            $stmt=$this->mysqlConnection->prepare($sqlCommand);
            if(!$stmt)
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            if(!$stmt->bind_param("iisss",$receiver,$sender,$subject,$content,$dateSent))
                throw new Exception("Failed to bind parameters.<br>".$this->mysqlConnection->error);
            if(!$stmt->execute())
                throw new Exception ("Unable to execute SQL Command.<br>".$this->mysqlConnection->error);
            $stmt->close();
        }
        public function MarkMessageAsRead($msg){
            $msgID=$msg->msg_id;
            $query="UPDATE private_messages SET Message_Read=1 WHERE MID=?";
            $stmt=$this->mysqlConnection->prepare($query);
            if(!$stmt)
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            if(!$stmt->bind_param("i",$msgID))
                throw new Exception("Failed to bind parameters.<br>".$this->mysqlConnection->error);
            if(!$stmt->execute())
                throw new Exception("Unable to execute SQL command.<br>".$this->mysqlConnection->error);
            $stmt->close();
        }
        public function AddLatestIP($uid,$ip){
            $sqlCommand = "UPDATE AccountInfo SET Account_IPAddresses=? WHERE UID=?";
            $stmt=$this->mysqlConnection->prepare($sqlCommand);
            $stmt->bind_param("si",htmlspecialchars($ip),$uid);
            $stmt->execute();
            $stmt->close();
        }
        public function ChangeProfilePicture($uid,$pictureURL){
            $sqlCommand = "UPDATE profile SET Profile_Picture=? WHERE UID=?";
            $stmt=$this->mysqlConnection->prepare($sqlCommand);
            if(!$stmt){
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            }
            if(!$stmt->bind_param("si",$pictureURL,$uid))
                throw new Exception("Failed to bind parameters.<br>".$this->mysqlConnection->error); 
            if(!$stmt->execute())
                throw new Exception("Unable to execute SQL command.<br>".$this->mysqlConnection->error);
            $stmt->close();
        }
        public function UpdateDescription($uid,$newDesc){
            $sqlCommand = "UPDATE profile SET Profile_Description=? WHERE UID=?";
            $stmt=$this->mysqlConnection->prepare($sqlCommand);
            if(!$stmt){
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            }
            if(!$stmt->bind_param("si",$newDesc,$uid))
                throw new Exception("Failed to bind parameters.<br>".$this->mysqlConnection->error); 
            if(!$stmt->execute())
                throw new Exception("Unable to execute SQL command.<br>".$this->mysqlConnection->error);
            $stmt->close();
        }
        public function AddFriend($uid,$fid){
            $sqlCommand = "INSERT INTO friends (UID,FriendID) VALUES (?,?)";
            $stmt=$this->mysqlConnection->prepare($sqlCommand);
            if(!$stmt){
                throw new Exception("Failed to prepare statement.<br>".$this->mysqlConnection->error);
            }
            if(!$stmt->bind_param("ii",$uid,$fid))
                throw new Exception("Failed to bind parameters.<br>".$this->mysqlConnection->error); 
            if(!$stmt->execute())
                throw new Exception("Unable to execute SQL command.<br>".$this->mysqlConnection->error);
            $stmt->close();
        }
    }
?>  
