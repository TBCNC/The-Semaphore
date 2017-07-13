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
              <h1>Profile settings</h1>
              <form id="settingsForm" action="../Functions/POSTPages/AccountManagement/UpdateProfileInfo.php" method="POST" enctype="multipart/form-data">
                  <div class="form-group">
                    <h3>Profile picture:</h3>
                    <input type="file" name="profilePic" id="profilePic" accept="image/jpeg,image/png">
                  </div>
                  <div class="form-group">
                      <?php
                        $curUID = $sesManager->GetCurrentUID();
                        include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/ProfileDetails.php");
                        $profile = new ProfileDetails($curUID);
                        echo "<h3>Description:</h3>";
                        if($profile->description!=NULL)
                            echo "<textarea name='description' class='form-control' style='resize:none;height:25%;' name='description'>".$profile->description."</textarea>";
                        else
                            echo '<textarea placeholder="Profile description" style="resize: none; height:25%;" name="description" class="form-control"></textarea>';
                      ?>
                      
                  </div>
                  <button type="submit" class="form-control">Update settings</button>
              </form>
          </div>
      </div>
  </body>
</html>
