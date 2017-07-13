<?php
    class ResultForwarding{
        private $result;
        function __construct($result){
            $this->result = $result;
        }
        function RedirectWithResult(){
            echo "<html>";
            echo "<form name='redirectForm' action='http://{$_SERVER["SERVER_NAME"]}/result/' method='POST'>";
            echo "<input type='hidden' name='result' id='result' value='{$this->result}'>";
            echo "</form>";
            echo "<script>";
            echo "document.redirectForm.submit();";
            echo "</script>";
            echo "</html>";
        }
    }
?>