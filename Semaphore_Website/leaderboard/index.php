<!DOCTYPE html>
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
    
    <script src="assets/js/modernizr.js"></script>
  </head>
  <style>
      
  </style>
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
          #leaderboard{
              border-color:#ccc;
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              font-size:28px;
              width:70%;
              
          }#leaderboardTable{
              font-size:28px;
              border-spacing:50px;
              border-collapse:separate;
              width:65%;
          }
      </style>

	
	 <div id="service">
	 	<div class="container">
                    <h1><center>Global leader-board:</center></h1>
                    <center>
                    <div id="leaderboard">
                        <center>
                        <table id="leaderboardTable">
                            <tr>
                                <td>Rank</td>
                                <td>User</td>
                                <td>Games won</td>
                                <td>Total points</td>
                            </tr>
                            <!--
                            <tr>
                                <td>1</td>
                                <td><a href='#'>Test</a></td>
                                <td>3</td>
                                <td>1000</td>
                            </tr>!-->
                            <?php
                                include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/Ranking/RankOrganizer.php");
                                include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/ProfileDetails.php");
                                $ranking = new RankOrganizer();
                                $scores = $ranking->GetLeaderboard();
                                $rankCounter=1;
                                foreach($scores as $score){
                                    $uid = $score[0];
                                    $wonGames = $score[1];
                                    $points = $score[2];
                                    $profileDetail = new ProfileDetails($uid);
                                    echo "<tr>";
                                    echo "<td>{$rankCounter}</td>";
                                    echo "<td><a href='../profile.php?id={$uid}'>{$profileDetail->username}</a></td>";
                                    echo "<td>{$wonGames}</td>";
                                    echo "<td>{$points}</td>";
                                    $rankCounter+=1;
                                }
                            ?>
                             
                        </table>
                        </center>
                    </div>
                    </center>
	 	</div><! --/container -->
	 </div><! --/service -->
	 

  </body>
</html>
