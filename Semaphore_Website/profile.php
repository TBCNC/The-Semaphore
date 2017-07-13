<!DOCTYPE html>
<?php
    include_once('./Functions/Security/InputSecurity.php');
    include_once('./Functions/Security/SessionManager.php');
    include_once('./Functions/SQLQuerying/DataVisualization.php');
    ini_set('display_errors',0);
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

	
	
	 <div id="service">
             <style>
                 #profileSummary{
                     margin-left:0;
                     border-color:#ccc;
                    -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
                    -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
                    box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
                    width:15%;
                    padding:10px;
                    position:absolute;
                    float:left;
                    word-wrap:break-word;
                 }
                 #profileMain{ 
                    border-color:#ccc;
                    -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
                    -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
                    box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
                    margin-left:27%;
                    width:80%;
                    padding:15px;
                    font-size:16px;
                 }
                 #friendsList{
                    border-color:#ccc;
                    -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
                    -webkit-box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
                    box-shadow:0px 2px 3px 0px rgba(0,0,0,0.2);
                    margin-left:27%;
                    width:80%;
                    padding:15px;
                    font-size:16px; 
                 }
                 #friends{
                     position:relative;
                 }
                 #friend{
                     float:left;
                     padding-right:20px;
                 }
                 #friendDiv{
                     text-align:center;
                 }
             </style>
	 	<div class="container">
                    <!--
                    <div id="profilePicture">
              
                    </div>!-->
                    <div id="profileInfo">
                        <?php
                        //Need a function to check whether the user exists
                            include_once("./Functions/SocialStuff/ProfileDetails.php");
                            include_once("./Functions/Security/InputSecurity.php");
                            $security = new InputSecurity();
                            $userID = $security->CleanseXSS($_GET["id"]);
                            try{
                                $profile = new ProfileDetails($userID);//Prepared statements already take care of the issue of SQL injection.
                                $profile->GetFriends();
                                echo '<div id="profileSummary">';
                                echo '<center>';
                                if(isset($profile->profilePicture))
                                    echo "<img src='".$profile->profilePicture."' width='128' height='128' />";
                                else
                                    echo "<img src='./images/default.png' width='128' height='128'/>";
                                echo "<h2>".$profile->username."</h2>";
                                echo "<h4>Age:".$profile->age."</h4>";
                                echo "<h4>Joined:".$profile->joinDate."</h4>";
                                echo "<h4>Country:".$profile->country."</h4>";

                                echo "<h4>Comps won:".$profile->compsWon."</h4>";

                                $list = $profile->friends;
                                $sesManager = new SessionManager();
                                $ourProfile=NULL;
                                if($sesManager->IsUserLoggedIn()){
                                    $ourProfile=new ProfileDetails($sesManager->GetCurrentUID());
                                    if(isset($list)){
                                        if(!in_array($ourProfile,$list) && ($sesManager->GetCurrentUID()!=$profile->userID)){
                                            echo "<a href='./Functions/POSTPages/Messaging/SendFriendRequest.php?friendID={$profile->userID}'>Send friend request.</a>";
                                        }
                                    }else{
                                        if($sesManager->GetCurrentUID()!=$profile->userID)
                                            echo "<a href='./Functions/POSTPages/Messaging/SendFriendRequest.php?friendID={$profile->userID}'>Send friend request.</a>";
                                    }
                                }

                                echo '</center>';

                                echo "</div>";

                                echo '<div id="profileMain">';
                                if(isset($profile->description))
                                    echo $profile->description;
                                else
                                    echo "<p>No description available.</p>";
                                echo '</div>';
                                echo"<br>";
                                echo "<div id='friendsList'>";
                                echo "<div id='headingContainer'>";
                                echo "<h2>Friends (".sizeof($list).")</h2>";
                                echo "<a href='./friends.php?id={$profile->userID}' style='margin-left:90%;'>See all</a>";
                                echo "</div>";
                            
                                echo "<div id='friends'>";
                                if(sizeof($list)>0){
                                    for($friend=0;$friend<5;$friend++){
                                        if(isset($list[$friend])){
                                            echo "<div id='friend'>";
                                            if(isset($list[$friend]->profilePicture))
                                                echo "<img src='".$list[$friend]->profilePicture."' width='64px' height='64px' />"; 
                                            else
                                                echo "<img src='http://{$_SERVER["SERVER_NAME"]}/images/default.png' width='64px' height='64px' />";
                                            echo "<h4><a href='./profile.php?id=".$list[$friend]->userID."'>".$list[$friend]->username."</a></h4>";
                                            echo "</div>";
                                        }else{
                                            break;
                                        }
                                    }
                                }else{
                                    echo "This user does not have any friends. :c Send them a friend request!";
                                }
                                echo "<br style='clear:left;'>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                            catch(Exception $e){
                                if($e->getCode()==0){
                                    echo "<center><h1>This user profile could not be found.</h1></center>";
                                }
                            }
                           
                        ?>
                    </div>
	 	</div><! --/container -->
	 </div><! --/service -->
	 

  </body>
</html>
