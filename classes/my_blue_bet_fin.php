<?php 

require_once('../config.php');

//if ( ! is_null  ($_settings->userdata('id'))  ){

      $qry = $conn->query("SELECT * FROM `bets`,`draws` WHERE draws.id=bets.drawid and bets.drawid = (select id FROM draws WHERE active = 'Y' ) and bets.user_id = {$_settings->userdata('id')}");  
      if($qry->num_rows > 0){
        $row = $qry->fetch_assoc();
        if($row['blue_amount'] >0){
        echo number_format($row['blue_amount'],2) .' = '. number_format($row['blue_amount']* $row['blue_payout'],2);
        }
      }else{
            echo '0';
      }  

?>