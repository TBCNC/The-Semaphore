<?php
    class Message{
        public $msg_id;
        public $msg_reciever;
        public $msg_sender;
        public $msg_subject;
        public $msg_conetnt;
        public $msg_date;
        public $msg_read;
        function __construct($id,$reciever,$sender,$subject,$content,$date,$read){
            $this->msg_id=$id;
            $this->msg_reciever=$reciever;
            $this->msg_sender=$sender;
            $this->msg_subject=$subject;
            $this->msg_content=$content;
            $this->msg_date=$date;
           //In mysql, 0=false and 1=true
            if($read==0)
                $this->msg_read=false;
            else
                $this->msg_read=true;
        }
    }
?>