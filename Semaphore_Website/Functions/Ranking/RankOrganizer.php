<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataVisualization.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SocialStuff/ProfileDetails.php");
    
    class RankOrganizer{
        function __construct(){
        }
        public function GetLeaderboard(){
            $datavis = new DataVisualization();
            $scores = $datavis->GetAllScores();
            unset($datavis);
            $idArray=array();
            $winArray=array();
            $scoreArray=array();
            
            foreach($scores as $key=>$row){
                $idArray[$key]=$row[0];
                $winArray[$key]=$row[1];
                $scoreArray[$key]=$row[2];
            }
            array_multisort($scoreArray,SORT_DESC,$idArray,SORT_DESC,$scores);
            return $scores;
        }
        public function GetRankOfUser($uid){
            $lb=$this->GetLeaderboard();
            $iterator=0;
            foreach($lb as $data){
                if($data[0]==$uid)
                    return ($iterator+1);
                $iterator+=1;
            }
        }
    }
    $rank = new RankOrganizer();
    $rank->GetLeaderboard();
   
?>