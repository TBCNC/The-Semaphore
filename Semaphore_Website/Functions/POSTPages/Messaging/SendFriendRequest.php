<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/Inbox.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/Message.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/Security/SessionManager.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/ProfileDetails.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/POSTPages/Front-end output/ResultForwarding.php");
    
    $ses = new SessionManager();
    
    $friendToRequestID=$_GET["friendID"];
    $personRequestingID=$ses->GetCurrentUID();
    $personrequestingProfile = new ProfileDetails($personRequestingID);
    $userInbox = new Inbox($personRequestingID);
    
    $subject="[Friend Request] {$personrequestingProfile->username} wants to add you!";
    $message="Automated message:<br>User <a href='../profile.php?id={$personRequestingID}'>{$personrequestingProfile->username}</a> has sent a friend request to you and wants to be your friend. <br> Click this link here to accept: <a href='http://".$_SERVER["SERVER_NAME"]."/Functions/POSTPages/AccountManagement/AcceptFriendRequest.php?id=".$personRequestingID."'>Accept request.</a><br>If you wish to not accept this request just ignore this message.";
    $date = date("Y-m-d H:i:s");
    $fullMsg = new Message(0,$friendToRequestID,$personRequestingID,$subject,$message,$date,0);
    $result="";
    try{
        $userInbox->SendMessage($fullMsg,true);
        $result="Sent friend request!";
    }catch(Exception $e){
        $result="There was an issue while sending your friend request.";
    }
    $redirector = new ResultForwarding($result);
    $redirector->RedirectWithResult();
?>
