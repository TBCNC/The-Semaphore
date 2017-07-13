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
          
      </style>
      <div id="service">
          <div class="container">
              <h1>Send Message</h1>
              <form id='msgForm' action='../Functions/POSTPages/Messaging/SendMessage.php' method='POST'>
              <div class="form-group">
                  <?php
                  if(!isset($_GET["user"]))  
                    echo "<input type='username' class='form-control' id='receiver' name='receiver' placeholder='Username'>";
                  else
                    echo "<input type='username' class='form-control' id='receiver' name='receiver' placeholder='Username' value='{$_GET["user"]}'>";
                  ?>
              </div>
              <div class='form-group'>
                  <?php
                  if(!isset($_GET["subject"]))  
                        echo "<input type='username' class='form-control' id='subject' name='subject' placeholder='Subject'>";
                  else
                        echo "<input type='username' class='form-control' id='subject' name='subject' placeholder='Subject' value='{$_GET["subject"]}'>";
                  ?>
              </div>
              <div class='form-group'>
                  <?php
                  if(!isset($_GET["body"]))
                       echo "<textarea class='form-control' placeholder='Message' style='resize:none; height:50%;' name='message' id='message'></textarea>";
                  else
                       echo "<textarea class='form-control' placeholder='Message' style='resize:none; height:50%;' name='message' id='message' value='{$_GET["body"]}'></textarea>";
                  ?>
              </div>
              <button type="submit" class="btn btn-primay">Send Message</button>    
              </form>
          </div>
      </div>
  </body>
</html>
