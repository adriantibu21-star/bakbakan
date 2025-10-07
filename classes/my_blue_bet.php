<?php 

require_once('../config.php');
//if ( ! is_null  ($_settings->userdata('id'))  ){
      $qry = $conn->query("SELECT * FROM `bets` WHERE drawid = (select id FROM draws WHERE active = 'Y' ) and user_id = {$_settings->userdata('id')}");  
      if($qry->num_rows > 0){
        $row = $qry->fetch_assoc();
        echo number_format($row['blue_amount'],2);

      } else{
            echo '0';
      }
      
//}else{
      //echo 'User was logged out!';
//}
?>



