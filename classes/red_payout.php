<?php 

require_once('../config.php');
      $red = $conn->query("SELECT red_payout*100 as payout FROM `draws` where active = 'Y' ");
      $rec_count = $red->num_rows;

      if ($rec_count>0){
            $row = $red->fetch_assoc();
            echo 'PAYOUT: '.number_format($row['payout'],2);
      }
      else{
            echo '';
      }

?>



