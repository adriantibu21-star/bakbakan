<?php 

require_once('../config.php');
      $blue =  $conn->query("SELECT blue_payout*100 as payout FROM `draws` where active = 'Y' ");
      $rec_count = $blue->num_rows;

      if ($rec_count>0){
            $row = $blue->fetch_assoc();
            echo 'PAYOUT: '.number_format($row['payout'],2);
      }
      else{
            echo '';
      }
   
?>



