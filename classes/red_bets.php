<?php 

require_once('../config.php');
      $redb = $conn->query("SELECT red FROM `draws` where active = 'Y' order by id desc limit 1");
      $rec_count = $redb->num_rows;

      if ($rec_count>0){
            $row = $redb->fetch_assoc();
            //echo number_format($row['red'],2);
            echo number_format($row['red'],2); 
      }
      else{
             echo '0.00';
      }

?>



