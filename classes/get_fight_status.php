<?php 
require_once('../config.php');

$eventid = $_GET['eventid'];

      $redb = $conn->query("SELECT red, red_payout*100 as red_payout, blue, blue_payout*100 as blue_payout FROM `draws` where eventid = '{$eventid}' and active = 'Y' order by id desc limit 1");
      $rec_count = $redb->num_rows;

      if ($rec_count>0){
		$row = $redb->fetch_assoc();

		$response = array('red' => number_format($row['red'],2), 'red_payout' => 'PAYOUT: '.number_format($row['red_payout'],2), 'blue' => number_format($row['blue'],2), 'blue_payout' => 'PAYOUT: '.number_format($row['blue_payout'],2));
		echo json_encode($response);
      }
      else{
		$response = array('red' => 0,'red_payout' => 0,'blue' => 0,'blue_payout' => 0);
		echo json_encode($response);

      }

?>






