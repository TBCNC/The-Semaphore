<!DOCTYPE html>
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
          <a class="navbar-brand" href="index.html">The Semaphore</a>
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
      <div id="service">
          <div class="container">
              <center>
                  <style>
                      #competitionTitle{
                          font-size:64px;
                      }
                      #competitionTopic{
                          font-size:32px;
                      }
                      #competitionTimer{
                          font-size:48px;
                      }
                      #descriptionBox{
                          font-size:32px;
                      }
                      #competitionDescription{
                          font-size:32px;
                      }
                      #playerCounter{
                          font-size:32px;
                      }
                      #competitionDifficulty{
                          font-size:32px;
                      }
                      #competitionFeedback{
                          font-size:24px;
                      }
                  </style>
                  <div id="mainCompetitionBox">
                      <div id="competitionTitle">
                        Connecting to TSCS... (The Semaphore Competition Server)
                      </div>
                      <div id="competitionTopic">
                      </div>
                      <div id="competitionDifficulty">
                          
                      </div>
                      <div id="competitionTimer">
                      </div>
                      <div id="competitionDescription">
                          
                      </div>
                      <div id="playerCounter">
                          
                      </div>
                      <div id="competitionAnswerBox">
                          <form id="mainForm">
                          </form>
                      </div>
                      <div id="competitionFeedback">
                          
                      </div>
                  </div>
              </center>
              <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
              <script src="./socket.io/socket.io.js"></script>
              <script>
                  //Here we are going to connect to the server, or at least attempt to.
                  jQuery(function($){
                    var socket = io.connect("http://82.39.29.160:666");//Replace this with the domain name when I register it and set up the server.
                    var titleBox = $('#competitionTitle');
                    var topicBox = $('#competitionTopic');
                    var difficultyBox = $('#competitionDifficulty');
                    var timerBox = $('#competitionTimer');
                    var descriptionBox = $('#competitionDescription');
                    var answerBox = $('#competitionAnswerBox');
                    var resultBox = $('#competitionFeedback');
                    var playerCountBox = $('#playerCounter');
                    var formBox = $('#mainForm');
                    var name = "<?php
error_reporting(E_ALL); 
include_once($_SERVER['DOCUMENT_ROOT'].'/Functions/Security/SessionManager.php');
$sesMan = new SessionManager();
echo $sesMan->GetCurrentUsername();
?>";
                    if(socket.connected){
                       titleBox.html("Connected to TSCS! Retrieving latest competition...");
                    }else{
                        titleBox.html("TSCS is offline! A competition may not be online right now. Try again later and check your homepage for when competitions are online.");
                    }
                    socket.emit('socketName',{name:name});
                    socket.on('startDate',function(data){
                        var title = data.title;
                        var topic = data.topic;
                        var timeToStart = data.timeToStart;
                        var jackpot = data.jackpot;
                        timerBox.show();
                        formBox.html("");
                        titleBox.html("Title:"+title);
                        topicBox.html("Topic:"+topic);
                        difficultyBox.html("Difficulty:"+data.compDifficulty)
                        timerBox.html("Time until competition starts:" + timeToStart);
                        descriptionBox.html("Current jackpot:"+jackpot+" points!");
                    });
                    socket.on('counterUpdate',function(data){
                        console.log("Received counter");
                        playerCountBox.html("There are " + data.players + " players online!");
                    });
                    
                    socket.on('startGame',function(data){
                       console.log("Starting game!");
                       timerBox.hide();
                       formBox.show();
                       titleBox.html("Title:"+data.title);
                       topicBox.html("Topic:"+data.compTopic);
                       descriptionBox.html(data.compDescription);
                       formBox.html("<input size='35' id='answerBox' /> <input type='submit' value='Check answer' />");
                    });
                    
                    socket.on('gameOver',function(data){
                        console.log("Someone has won!");
                        answerBox.hide();
                        resultBox.hide();
                        descriptionBox.html(data.name + " has solved the puzzle!<br>The answer was "+data.answer+"!<br>"+data.name+" has won a jackpot of "+data.jackpot +" points!<br>Thank you for participating!");//Later on make it so the name is a link to the profile using AJAX
                    });
                    socket.on('wrongAnswer',function(data){
                        resultBox.html("That answer is wrong! Try again.");
                    });
                    formBox.submit(function(e){
                        e.preventDefault();
                        var answer = $('#answerBox').val();
                        socket.emit('answer',{answer:answer});
                    });
                  });
              </script>
          </div>
      </div>
  </body>
</html>
