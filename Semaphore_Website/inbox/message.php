<?php            
        error_reporting(E_ALL);
        ini_set('display_errors','1');
        include_once('../Functions/Security/SessionManager.php');
        $sesManager = new SessionManager();
        if(!$sesManager->IsUserLoggedIn())
            header('Location:../login');
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">

    <title>The Semaphore-Ethical Hacking Society</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script src="../assets/js/modernizr.js"></script>
  </head>
  <body>

    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="../">The Semaphore</a>
        </div>
        <div class="navbar-collapse collapse navbar-right">
          <ul class="nav navbar-nav">
            <?php
                include_once('../Functions/Cosmetic Stuff/LinksToCreate.php');
                $linkCreator = new LinkCreation();
                $linkCreator->CreateLinks();
                
            ?>
          </ul>
        </div>
      </div>
    </div>
      <style>
          #messageCounter{
              font-size:48px;
              padding-bottom:10px;
          }
          #inboxTable td{
              padding:20px;
          }
          #messageButton{
              float:right;
          }
          #mainMessage{
              border-color:#ccc;
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              font-size:24px;
              padding:10px;
          }
      </style>
      <div id="service">
          <div class="container">
                   <div id="messageCounter">
                       <?php
                            include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/Inbox.php");
                            include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/ProfileDetails.php");
                            include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataVisualization.php");
                            $inbox=new Inbox($sesManager->GetCurrentUID());
                            $msgs = $inbox->RetrieveInbox();
                            $msgs=array_reverse($msgs);
                            $msgIndx = $_GET["ind"];
                            $theMessage = $msgs[$msgIndx];
                            if($theMessage->msg_read==0)
                                $inbox->MarkMessageAsRead ($theMessage);
                            echo $theMessage->msg_subject;
                       ?>
                       <div id="messageButton">
                           <?php
                                $dataVis = new DataVisualization();
                                $senderName=$dataVis->GetUsername($theMessage->msg_sender);
                                unset($dataVis);
                                $newBody = "------------------<br>".$theMessage->msg_content;
                                echo '<a href="./send.php?user='.$senderName.'&subject=RE:'.$theMessage->msg_subject.'&body='.$newBody.'">Reply</a>';
                           ?>
                       </div>
                   </div>
              <div id='mainMessage'>
                  <?php
                  
                  echo "Date sent:".$theMessage->msg_date."<br>";
                  //echo "From:".$senderName."<br>";
                  echo "Message:<br>".$theMessage->msg_content;
                  ?>
              </div>
          </div>
      </div>
  </body>
</html>
