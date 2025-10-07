<?php 

require_once('../config.php');
      $blueb = $conn->query("SELECT blue FROM `draws` where active = 'Y' order by id desc limit 1 ");
      $rec_count = $blueb->num_rows;

      if ($rec_count>0){
            $row = $blueb->fetch_assoc();
            //echo number_format($row['blue'],2);
            echo number_format($row['blue'],2); 
      }
      else{
            echo '0.00';
      }

      
?>