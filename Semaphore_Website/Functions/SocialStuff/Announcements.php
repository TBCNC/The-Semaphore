<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Functions/SQLQuerying/DataVisualization.php");
    class Announcements{
        function __construct(){
            
        }
        public function GetLatestAnnouncements(){
            $dataVis = new DataVisualization();
            $announcements = $dataVis->GetAllAnnouncements();
            //Because the announcements are put in by auto_increment, we can assume that the array backwards is recent-oldest
            $announcements = array_reverse($announcements);
            $returnAnnouncements = array();
            for($x=0;$x<3;$x++){//Going to get three latest announcements
                if(isset($announcements[$x]))
                    array_push($returnAnnouncements,$announcements[$x]);
                else
                    break;
            }
            return $returnAnnouncements;
        }
    }
?>