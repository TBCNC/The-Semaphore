<?php
//In future make this more secure by using a different method of get such as encrypting the user id with a private key
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataCreation.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/Inbox.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/Message.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/Security/SessionManager.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/POSTPages/Front-end output/ResultForwarding.php");
    
    $friendID = $_GET["id"];
    $ses = new SessionManager();
    $ourID = $ses->GetCurrentUID();
    $dataCreation = new DataCreation();
    $dataCreation->AddFriend($ourID, $friendID);
    $dataCreation->AddFriend($friendID,$ourID);
    //Added friend, now let's send a message saying the request has been accepted.
    $inbox = new Inbox($ourID);
    $subject="[Request accepted!] Your request has been accepted by {$ses->GetCurrentUsername()}!";
    $content="Congratulations, the friend request you sent to {$ses->GetCurrentusername()} has been accepted. You are now friends.";
    $date = date("Y-m-d H:i:s");
    $fullMsg = new Message(0,$friendID,$ourID,$subject,$content,$date,0);
    $inbox->SendMessage($fullMsg, false);
    unset($dataCreation);
    $result = "Request accepted!";
    $redirector = new ResultForwarding($result);
    $redirector->RedirectWithResult();
?>