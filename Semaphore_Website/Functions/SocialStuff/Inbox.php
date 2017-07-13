<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/Message.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataVisualization.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataCreation.php");
    //When setting up the inbox service you will want to traverse backwards through the array to get the latest messages instead of the oldest.
    class Inbox{
        private $userID;
        function __construct($userID){//This is going to be the user id of the inbox we want
            $this->userID=$userID;
        }
        public function RetrieveInbox(){
            $id=$this->userID;
            $dataVis = new DataVisualization();
            $msgs = $dataVis->GetAllMessages($id);
            unset($dataVis);
            return $msgs;
        }
        public function GetUnreadAmount(){
            $dataVis = new DataVisualization();
            $amount = $dataVis->GetMessagesUnreadAmount($this->userID);
            unset($dataVis);
            return $amount;
        }
        public function MarkMessageAsRead($msg){
            $dataCreation = new DataCreation();
            $dataCreation->MarkMessageAsRead($msg);
            unset($dataCreation);
        }
        public function SendMessage($msg,$frq){
            $dataCreation = new DataCreation();
            $dataCreation->SendMessage($msg,$frq);
            unset($dataCreation);
        }
    }
?>