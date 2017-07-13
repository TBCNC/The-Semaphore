<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/Security/InputSecurity.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/Security/SessionManager.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataCreation.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/ProfileDetails.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/POSTPages/Front-end output/ResultForwarding.php");
    
    $sesMan = new SessionManager();
    if(!$sesMan->IsUserLoggedIn()){
        header('location:../../../');
    }
    $result="";
    //Maybe test this page with php payload testing.
    $description = $_POST["description"];
    $profilePic = $_FILES["profilePic"];
    $dataCre = new DataCreation();
    if(isset($profilePic) || isset($description)){
        if(isset($profilePic)){
            $fileName = $profilePic['name'];
            $fileExt = substr($fileName,strrpos($fileName,'.')+1);
            $fileSize = $profilePic['size'];
            $fileType = $profilePic['type'];
            $fileTmp = $profilePic['tmp_name'];

            $newLocation = '../../../images/';
            $inputSec = new InputSecurity();
            $newFileName = $inputSec->HashPassword($fileName.$inputSec->GenerateVerificationCode()).".".$fileExt;
            $newFileName = str_replace("/","",$newFileName);
            $url = "http://".$_SERVER['SERVER_NAME']."/images/".$newFileName;
            
            if(((strtolower($fileExt)=='jpeg')||(strtolower($fileExt)=='jpg')||(strtolower($fileExt)=='png'))
                    && ($fileType=='image/jpeg' || $fileType=='image/png')
                    && getimagesize($fileTmp)){
                if($fileSize<524288){//This is equivalent to half a megabyte
                    //Continue with upload
                    move_uploaded_file($fileTmp,$newLocation.$newFileName);
                    //Now the file is uploaded, let's change it in the database.
                    $dataCre->ChangeProfilePicture($sesMan->GetCurrentUID(), $url);
                    //We are done.
                    $result="Profile picture changed!";
                }else{
                    //Image too large.
                    $result="Failed to change profile picture. Image too large. Images must be less than 0.5MB!";
                }
            }else{
                //Did not upload an image.
                $result="Failed to upload image. This is not an image format we accept. We only accept JPG and PNG";
            }
        }
        if(isset($description)){
            $curProfile = new ProfileDetails($sesMan->GetCurrentUID());
            if($curProfile->description!=$description){
                $dataCre->UpdateDescription($sesMan->GetCurrentUID(), $description);
                $result.="<br>Updated description!";
                unset($dataCre);
            }//Otherwise we do not need to change the description
        }
    }else{
        header('location:../../../');
    }
    $redirector = new ResultForwarding($result);
    $redirector->RedirectWithResult();
?>