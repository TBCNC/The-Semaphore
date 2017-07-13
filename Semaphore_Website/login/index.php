<!DOCTYPE html>
<?php            
        
        error_reporting(E_ALL);
        ini_set('display_errors','1');
        include_once('../Functions/Security/SessionManager.php');
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
          #loginForm{
          }
          #loginContainer{
           padding-top:5%;
           padding-bottom:5%;
              height:20%;
              width:30%;
              border-color:#ccc;
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
              box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
          }
      </style>

	
	 <div id="service">
	 	<div class="container" id="loginContainer">
                    <div id="loginForm">
                        <h1>Login</h1>
                        <form action="../Functions/POSTPages/AccountManagement/VerifyLogin.php" method="post">
                            <div class="form-group">
                                <input type="username" class="form-control" id="usernameField" name="username" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="passwordField" name="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-primay">Login</button>
                        </form>
                        
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
                                        <p>This website does not condone or support any kind of illegal activity against users or people. We reserve the right to suspend your account if you partake in any illegal activity because of what you learn from this website. We are not responsible on how you use this information.</p>
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
  </body>
</html>
