<?php 

require_once('../config.php');

$user_id = $_settings->userdata('id');
$betid = $_POST['betid'];
$bet = $_POST['bet'];
$date_created = date("Y-m-d H:i");
$id = '';
$eventid = $_POST['eventid'];

if($betid > 0){

    $qry_betid = $conn->query("SELECT id FROM `bets` WHERE drawid = (select id FROM draws WHERE eventid = '{$eventid}' and active = 'Y' order by id desc limit 1 ) and user_id = '{$user_id}'");
    
        if($qry_betid->num_rows > 0){
		$row_betid = $qry_betid->fetch_assoc();
		$id = $row_betid['id'];
        }

}


$red_amount = '0.00';
$blue_amount = '0.00';
$yellow_amount = '0.00';

		if($betid == '1'){
			$red_amount = $bet;
			$blue_amount = '0.00';
			$yellow_amount = '0.00';
		}
		if($betid == '2'){
			$red_amount = '0.00';
			$blue_amount = $bet;
			$yellow_amount = '0.00';
		}
		if($betid == '3'){
			$red_amount = '0.00';
			$blue_amount = '0.00';
			$yellow_amount = $bet;
		}



		$check_close_draw = $conn->query("SELECT id FROM `draws` where `eventid` = '{$eventid}' and `active` = 'Y' order by id desc limit 1 ");
		if($check_close_draw->num_rows > 0){
		
			//check if fight is closed
			$check_close = $conn->query("SELECT id FROM `draws` where `eventid` = '{$eventid}' and `active` = 'Y' and status = '3' order by id desc limit 1 "); //lagyan ng staus = !open !lastcall
			if($check_close->num_rows > 0){

				$resp['status'] = 'error';
				$resp['msg'] = " FIGHT IS CLOSE!";
				echo json_encode($resp);

				exit;			
			}

		}else
		{

			$resp['status'] = 'error';
			$resp['msg'] = " NO ACTIVE DRAW!";
			echo json_encode($resp);

			exit;	
		}	
	



		//check sufficient balance
		$check_bal = $conn->query("SELECT amount FROM `users` where `id` = '{$user_id}' ");
		//if(capture_err())
		//	echo capture_err();
		if($check_bal->num_rows > 0){
			$row = $check_bal->fetch_assoc();

				if ($row['amount']< $bet ) {

					$resp['status'] = 'error';
					$resp['msg'] = " INSUFFICIENT BALANCE!";
					echo json_encode($resp);

					exit;
				}

			
		}else{
			//walang record sa my_balance table

			$resp['status'] = 'error';
			$resp['msg'] = " DEACTIVATED ACCOUNT!";
			echo json_encode($resp);

			exit;

		}

		//minimum bet

			if ($bet < 10 ) {
				$resp['status'] = 'error';
				$resp['msg'] = " INSUFFICIENT BET";
				echo json_encode($resp);
				exit;
			}	
		


		//check maximum bet 5000000
		$check_max = $conn->query("SELECT red_amount, blue_amount, yellow_amount FROM `bets` where drawid = (Select id from draws where `eventid` = '{$eventid}' and `active` = 'Y' order by id desc limit 1) and user_id = '{$user_id}' "); //lagyan ng staus = !open !lastcall
		if($check_max->num_rows > 0){
				$row = $check_max->fetch_assoc();

				if( ($row['red_amount'] + $red_amount) > 5000000 or ($row['blue_amount'] + $blue_amount) > 5000000 or ($row['yellow_amount'] + $yellow_amount) > 5000000 ){

					$resp['status'] = 'error';
					$resp['msg'] = " MAXIMUM BET REACHED!";
					echo json_encode($resp);

					exit;	
				}
		
		}else{
			if($bet > 5000000){
				$resp['status'] = 'error';
				$resp['msg'] = " MAXIMUM BET REACHED!";
				echo json_encode($resp);
				exit;	
			}			
		}




		if(empty($id)){

		    //drawid
		    $drawqry = $conn->query("SELECT id, (select odds from template where game_id = (select game_id from events where id = '{$eventid}')) odds from draws where `eventid` = '{$eventid}' and active ='Y' order by id desc limit 1 ");
			if($drawqry->num_rows > 0){
				$drow = $drawqry->fetch_assoc();
				$drawid = $drow['id'];
				$odds = $drow['odds'];
			}

			try {
			
			$conn->query("set autocommit=0");
			$conn->query("start transaction");

			//sql bind parameters
			$sql = $conn->prepare("INSERT into bets set drawid =?, user_id=?, date_created= ?, red_amount= ?, blue_amount= ?, yellow_amount= ? ");
			$sql->bind_param("iisddd", $drawid,$user_id, $date_created, $red_amount, $blue_amount, $yellow_amount);
			$sql->execute();


			//dito ilagay ung payout percentage
			
			//sql bind parameters
			$sql1 = $conn->prepare("UPDATE draws set red=red+?, blue=blue+?, yellow=yellow+? where id='{$drawid}' ");
			$sql1->bind_param("ddd",$red_amount,$blue_amount,$yellow_amount);
			$sql1->execute();


			//wag na baguhin wlang userinput
			$sql5 = "UPDATE draws set red_payout = COALESCE(((red+blue) * '{$odds}')/NULLIF(red, 0), 0.00), blue_payout = COALESCE(((red+blue) * '{$odds}')/NULLIF(blue, 0), 0.00) where id='{$drawid}' ";		
			$save5 = $conn->query($sql5);

			//sql bind parameters
			$sql2 = $conn->prepare("UPDATE users set amount =amount-(?+?+?) where id='{$user_id}' ");
			$sql2->bind_param("ddd",$red_amount,$blue_amount,$yellow_amount);
			$sql2->execute();

			$conn->query("commit");
			$conn->query("set autocommit=1");

			$resp['status'] = 'success';
			$resp['msg'] = "BET SUCCESSFULLY PLACED!";
	

			} catch (Exception $e) {

				$conn->query("rollback");
				$conn->query("set autocommit=1");

				$resp['status'] = 'error';
				$resp['msg'] = $e->getMessage();

			}

	

		}else{


		    //drawid
		    	$drawqry = $conn->query("SELECT id, (select odds from template where game_id = (select game_id from events where id = '{$eventid}')) odds from draws where eventid = '{$eventid}' and active ='Y' order by id desc limit 1 ");
			if($drawqry->num_rows > 0){
				$drow = $drawqry->fetch_assoc();
				$drawid = $drow['id'];
				$odds = $drow['odds'];
			}else{

				$resp['status'] = 'error';
				$resp['msg'] = " NO ACTIVE DRAW!";

			}

			try {
			
			$conn->query("set autocommit=0");
			$conn->query("start transaction");

			//sql bind parameters			
			$sql = $conn->prepare("UPDATE bets set red_amount = red_amount+?, blue_amount=blue_amount+?, yellow_amount=yellow_amount+? where id = '{$id}' ");
			$sql->bind_param("ddd", $red_amount, $blue_amount, $yellow_amount);
			$sql->execute();

			
			//sql bind parameters
			$sql1 = $conn->prepare("UPDATE draws set red=red+?, blue=blue+?, yellow=yellow+? where id='{$drawid}'");
			$sql1->bind_param("ddd",$red_amount,$blue_amount,$yellow_amount);
			$sql1->execute();

			$sql5 = "UPDATE draws set red_payout = COALESCE(((red+blue) * '{$odds}')/NULLIF(red, 0), 0.00), blue_payout = COALESCE(((red+blue) * '{$odds}')/NULLIF(blue, 0), 0.00) where id='{$drawid}' ";		
			$save5 = $conn->query($sql5);


			//sql bind parameters
			$sql2 = $conn->prepare("UPDATE users set amount =amount-(?+?+?) where id='{$user_id}' ");
			$sql2->bind_param("ddd",$red_amount,$blue_amount,$yellow_amount);
			$sql2->execute();
			//$sql2 = "UPDATE users set amount = amount - ('{$red_amount}'+'{$blue_amount}'+'{$yellow_amount}') where id='{$user_id}' ";
			//$save2 = $conn->query($sql2);

			$conn->query("commit");
			$conn->query("set autocommit=1");

			$resp['status'] = 'success';
			$resp['msg'] = "BET SUCCESSFULLY PLACED!";


			} catch (Exception $e) {

				$conn->query("rollback");
				$conn->query("set autocommit=1");

				$resp['status'] = 'error';
				$resp['msg'] = $e->getMessage();

			}

		}

		echo json_encode($resp);

?>
