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
          #welcomeBox{
              border-color:#ccc;
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              font-size:28px;
              height:300px;
              position:relative;
          }
          #profilePicture{
              float:left;
              padding:20px;
          }
          #welcomeMsg{
              padding-top:75px;
              float:right;
              padding-right:15px;
              text-align:right;
          }
          #announcementBox{
              font-size:28px;border-color:#ccc;
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
          }
          #mainAnnouncements{
              font-size:28px;
              padding:20px;
          } 
          .announcement{
              font-size:28px;
              border-top-color: #ccc;
              border-top-style:dashed;
              border-top-width:1px;
          }
          .title{
              font-size:48px;
          }
          .date{
              font-size:18px;
              float:right;
              text-align:right;
              padding-top:10px;
          }
          .body{
              font-size:24px;
          }
      </style>
      <div id="service">
          <div class="container">
              <div id="welcomeBox">
                  <div id="profilePicture">
                      <?php
                            include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/ProfileDetails.php");
                            $profile = new ProfileDetails($sesManager->GetCurrentUID());
                            if(isset($profile->profilePicture))
                                echo "<img src='{$profile->profilePicture}' height='256' width='256' />";
                            else
                                echo "<img src='http://{$_SERVER["SERVER_NAME"]}/images/default.png' height='256' width='256' />";
                      ?>
                  </div>
                  <div id="welcomeMsg">
                      <?php
                            echo "Welcome to The Semaphore {$profile->username}!<br>";
                            echo "Games won:{$profile->compsWon}<br>";
                            echo "Total points:{$profile->pointsTotal} points<br>";
                            echo "Current point amount:{$profile->pointsCurrent} points";
                      ?>
                  </div>
              </div>
              <br>
              <div id="announcementBox">
                  <div id="announcementHeader">
                      <i class="fa fa-comment"></i>Announcements
                  </div>
                  <div id="mainAnnouncements">
                      <?php
                        include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/Announcements.php");
                        $announcements = new Announcements();
                        $allAnnouncements = $announcements->GetLatestAnnouncements();
                        
                        foreach($allAnnouncements as $announcement){
                            $title=$announcement[3];
                            $body=$announcement[1];
                            $date=$announcement[2];
                            echo "<div class='announcement'>";
                            echo "<div class='title'>";
                            echo $title;
                            echo "<div class='date'>";
                            echo $date;
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='body'>";
                            echo $body;
                            echo "</div>";
                            echo "</div>";
                        }
                      ?>
                  </div>
              </div>
          </div>
      </div>
  </body>
</html>
