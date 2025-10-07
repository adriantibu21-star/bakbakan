<?php 

require_once('../config.php');
$eventid = $_GET['eventid'];
//if ( ! is_null  ($_settings->userdata('id'))  ){

      $qry = $conn->query("SELECT red_amount,red_payout,blue_amount,blue_payout,yellow_amount,
(SELECT sum(red_amount) FROM `bets` WHERE drawid = (select id FROM draws WHERE eventid = '{$eventid}' and active = 'Y' ) and user_id <> {$_settings->userdata('id')}) red_amount2, (SELECT sum(blue_amount) FROM `bets` WHERE drawid = (select id FROM draws WHERE eventid = '{$eventid}' and  active = 'Y' ) and user_id <> {$_settings->userdata('id')}) blue_amount2 FROM `bets`,`draws` WHERE draws.id=bets.drawid and bets.drawid = (select id FROM draws WHERE eventid = '{$eventid}' and active = 'Y' ) and bets.user_id = {$_settings->userdata('id')}");  

      if($qry->num_rows > 0){
        $row = $qry->fetch_assoc();
		$response = array('red' => number_format($row['red_amount'],2) .' = '. number_format($row['red_amount']* $row['red_payout'],2), 'blue' => number_format($row['blue_amount'],2) .' = '. number_format($row['blue_amount']* $row['blue_payout'],2), 'yellow' => number_format($row['yellow_amount'],2) .' = '. number_format($row['yellow_amount']* 8,2), 'red2' => number_format($row['red_amount2'],2), 'blue2' => number_format($row['blue_amount2'],2));
		echo json_encode($response);
      }
      else{
		$response = array('red' => 0, 'blue' => 0, 'yellow' => 0,'red2' => 0, 'blue2' => 0);
		echo json_encode($response);

      }

?>



