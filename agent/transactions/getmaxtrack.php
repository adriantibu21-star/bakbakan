<?php
require_once('../../config.php');
 $query = "SELECT MAX(trackingno) from transactions";
 $result = mysqli_query($conn,$query);
         if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_array($result);
            $lastid=$row[0];
                if  (substr($lastid,0,4) == substr(date("ym"),0,4)){
                    $empid = $lastid + 1 ;
                    }
                    else{
                $empid = date("ym") . "000001";
                    }
         }
         else{
           $empid = date("ym"). "000001";
         }
 echo $empid;
?>



