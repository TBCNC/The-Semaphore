<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Functions/SQLQuerying/DataVisualization.php");
include_once($_SERVER['DOCUMENT_ROOT']."/Functions/Security/SessionManager.php");
class ProfileDetails{
    public $userID;
    public $username;
    public $age;
    public $joinDate;
    public $profilePicture;
    public $description;
    public $specialTitle;
    public $country;
    public $friends;
    public $compsWon;
    public $pointsTotal;
    public $pointsCurrent;
    private $dataVis;
    
    public function __construct($userID){
        $this->userID = $userID;
        $this->dataVis = new DataVisualization();
        $this->username = $this->dataVis->GetUsername($userID);
        $dob = $this->dataVis->GetDOB($userID);
        $this->age = date_diff(date_create($dob),date_create("now"))->y;
        $this->joinDate = $this->dataVis->GetJoinDate($userID);
        $this->profilePicture = $this->dataVis->GetProfilePicture($userID);
        $this->description = $this->dataVis->GetProfileDescription($userID);
        
        if($this->dataVis->SpecialUser($userID)){
            $this->specialTitle=$this->dataVis->GetUserSpecialRole($userID);
        }else{
            $this->specialTitle=NULL;
        }
        $this->country = $this->dataVis->GetCountry($userID);
        $this->compsWon = $this->dataVis->GetTotalWins($userID);
        if($this->compsWon==NULL)
            $this->compsWon=0;
        $this->pointsTotal=$this->dataVis->GetCurrentPoints($userID);
        $this->pointsCurrent=$this->dataVis->GetTotalPoints($userID);
        unset($this->dataVis);
    }
    public function GetFriends(){//Used to prevent recursion
       $newVis = new DataVisualization();
       $this->friends = $newVis->GetAllFriends($this->userID);
       unset($newVis);
    }
    
}
