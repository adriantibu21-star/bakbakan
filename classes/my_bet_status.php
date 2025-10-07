<?php 

require_once('../config.php');
$eventid = $_GET['eventid'];

      $qry = $conn->query("SELECT sum(red_amount) red_amount,sum(blue_amount) blue_amount,sum(yellow_amount) yellow_amount,
(SELECT sum(red_amount) FROM `bets` WHERE drawid = (select id FROM draws WHERE eventid = '{$eventid}' and active = 'Y' ) and user_id <> {$_settings->userdata('id')}) red_amount2, (SELECT sum(blue_amount) FROM `bets` WHERE drawid = (select id FROM draws WHERE eventid = '{$eventid}' and  active = 'Y' ) and user_id <> {$_settings->userdata('id')}) blue_amount2 FROM `bets` WHERE drawid = (select id FROM draws WHERE eventid = '{$eventid}' and active = 'Y' ) and user_id = {$_settings->userdata('id')}");  
      if($qry->num_rows > 0){
        $row = $qry->fetch_assoc();
		$response = array('red' => number_format($row['red_amount'],2), 'blue' => number_format($row['blue_amount'],2), 'yellow' => number_format($row['yellow_amount'],2), 'red2' => number_format($row['red_amount2'],2), 'blue2' => number_format($row['blue_amount2'],2));
		echo json_encode($response);
      }
      else{
		$response = array('red' => 0, 'blue' => 0, 'yellow' => 0,'red2' => 0, 'blue2' => 0);
		echo json_encode($response);

      }
      

?>

