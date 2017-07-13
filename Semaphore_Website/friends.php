<?php
error_reporting(E_ALL);
        ini_set('display_errors','1');
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
                include_once('./Functions/Cosmetic Stuff/LinksToCreate.php');
                $linkCreator = new LinkCreation();
                $linkCreator->CreateLinks();
                
            ?>
          </ul>
        </div>
      </div>
    </div>
      <style>
          #friendsTable{
              font-size:28px;
              border-spacing:20px;
              border-collapse:separate;
              text-align:center;
              border-color:#ccc;
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
          }
      </style>
      <div id="service">
          <div class="container">
              <div id="allFriends">
                  <div id="friendHeader">
                  <?php
                    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/ProfileDetails.php");
                    $profile = new ProfileDetails($_GET["id"],true);
                    $profile->GetFriends();
                    echo "<h1><center>{$profile->username}'s friends (".sizeof($profile->friends)."):</h1></center>";
                  ?>
                  </div>
                  <!--
                  <table id="friendsTable">
                    <tr>
                        <td>
                            <img src="http://192.168.0.10/images/$2y$12$ckDZR3AxBG9PsWs5nF2ceoR4rd8k3GMka7vzGAxjlTMSsFHb7Hu.png" width="128" height="128"/>
                            <br>
                            <a href="#">User1</a>
                        </td>
                        
                    </tr>!-->
                  <?php
                  if(sizeof($profile->friends)>0){
                    echo '<table id="friendsTable">';

                          $friends=$profile->friends;
                          $iterationCounter=0;
                          while($iterationCounter<sizeof($friends)){
                              echo "<tr>";
                              for($c=0;$c<8;$c++){
                                  if(isset($friends[$c])){
                                      echo "<td>";
                                      if(isset($friends[$c]->profilePicture))
                                        echo "<img src='{$friends[$c]->profilePicture}' width='128' height='128' />";
                                      else
                                        echo "<img src='./images/default.png' width='128' height='128' />";
                                      echo "<br>";
                                      echo "<a href='./profile.php?id={$friends[$c]->userID}'>{$friends[$c]->username}</a>";
                                      echo "</td>";
                                  }else{
                                      break;
                                  }
                                  $iterationCounter=$iterationCounter+1;
                              }
                              echo "</tr>";
                          }

                    echo '</table>';
                  }else{
                      echo "<h1><center>This user does not have any friends. :c</center></h1>";
                  }
                  ?>
              </div>
          </div>
      </div>

  </body>
</html>
