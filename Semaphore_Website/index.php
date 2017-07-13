<!DOCTYPE html>
<?php            
        error_reporting(E_ALL);
        ini_set('display_errors','1');
        include_once('./Functions/Security/SessionManager.php');
        $sesManager = new SessionManager();
        if($sesManager->IsUserLoggedIn())
            header('Location:../UserHomepage/');
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.ico">

    <title>The Semaphore-Ethical Hacking Society</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">


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
          <a class="navbar-brand" href="index.html">The Semaphore</a>
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

	
	<div id="headerwrap" height='100px'>
	    <div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<h1>Welcome to The Semaphore!</h1>
					<h5>Competitive Ethical Hacking, Programming, Reverse-Engineering and Security community!</h5>				
				</div>
			</div>
	    </div> 
	</div>

	
	 <div id="service">
	 	<div class="container">
 			<div class="row centered">
 				<div class="col-md-4">
 					<i class="fa fa-flag-checkered"></i>
 					<h4>Race other users for Bitcoin!</h4>
 					<p>Enter live races set up every week in a variety of topics in which you hack in a race against other users, in order to have a chance to win Bitcoin sent directy to your bitcoin wallet!</p>
 				</div>
 				<div class="col-md-4">
 					<i class="fa fa-code"></i>
 					<h4>Practice your programming and hacking skills!</h4>
 					<p>No matter your skill, you have a selection of many puzzles, insecure web solutions, insecure programs and other challenges produced by the community to improve your skills!</p>
 				</div>
 				<div class="col-md-4">
 					<i class="fa fa-trophy"></i>
 					<h4>Rise above others!</h4>
 					<p>Hack and code your way up our leaderboard in order to show off your skill to other people and earn special rewards!</p>
 				</div>
	 		</div>
                        <div class="row centered">
                            <div class="col-md-4">
 					<i class="fa fa-user"></i>
 					<h4>Meet people like yourself!</h4>
 					<p>Meet like-minded security enthusiasts and programmers to collaborate and share your knowledge with in future projects!</p>
 				</div>
                                <div class="col-md-4">
 					<i class="fa fa-users"></i>
 					<h4>Team up!</h4>
 					<p>Create teams with your friends to take part in competitions and to solve challenges together!</p>
 				</div>
                                <div class="col-md-4">
 					<i class="fa fa-heart"></i>
 					<h4>Contribute to the community!</h4>
 					<p>Create challenges for other users to solve or contribute to our bug bounty program to keep our website as safe as possible! Your puzzle may be featured on the website for other people to see!</p>
 				</div>
                        </div>
	 	</div><! --/container -->
	 </div><! --/service -->
	 
	 <div id="footerwrap">
	 	<div class="container">
		 	<div class="row">
		 		<div class="col-lg-4">
		 			<h4>About</h4>
		 			<div class="hline-w"></div>
		 			<p>Website created by: Charles Hampton-Evans (tbcnc.co.uk)</p>
		 		</div>
		 		<div class="col-lg-4">
		 			<h4>Disclaimer/Legal</h4>
		 			<div class="hline-w"></div>
                                        <p>This website does not condone or support any kind of illegal activity against users or people. We reserve the right to suspend your account if you partake in <i>any</i> illegal activity.</p>
                                        <p>You can read our full <a href="#">Terms of service and privacy policy.</a> for more information.</p>
		 		</div>
		 		<div class="col-lg-4">
		 			<h4>Contact</h4>
		 			<div class="hline-w"></div>
                                        <p>Email:chasjohnh@gmail.com</p>
		 		</div>
		 	
		 	</div><! --/row -->
	 	</div><! --/container -->
	 </div><! --/footerwrap -->
	 
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

  </body>
</html>
