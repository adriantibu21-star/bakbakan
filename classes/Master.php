<?php
require_once('../config.php');
require_once('../libs/phpqrcode/qrlib.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		$this->permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}

	function save_role(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v = addslashes($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `roles` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Record already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `roles` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `roles` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New record successfully saved.");
			else
				$this->settings->set_flashdata('success',"Record successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function save_template(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v = addslashes($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `template` where game_id = '{$game_id}' and `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Template already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `template` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `template` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Record successfully saved.");
			else
				$this->settings->set_flashdata('success',"Record successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function save_events(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v = addslashes($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `events` where `game_id` = '{$game_id}' and `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Event already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `events` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `events` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Record successfully saved.");
			else
				$this->settings->set_flashdata('success',"Record successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function save_commission(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v = addslashes($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}

		if(empty($id)){
			$sql = "INSERT INTO `coms` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `coms` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Record successfully saved.");
			else
				$this->settings->set_flashdata('success',"Record successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function save_withdrawals(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v = addslashes($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}

		//check if there is a pending withdrawals for a certain agent
		$check = $this->conn->query("SELECT * FROM `withdrawals` where `user_id` = '{$user_id}' and active = 'Y' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Pending withdrawal already exist.";
			return json_encode($resp);
			exit;
		}

		
		//check if amount is zero
		if($amount <= 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Invalid amount!";
			return json_encode($resp);
			exit;			
		}

		if(empty($id)){
			//sql bind parameters
			$sql = $this->conn->prepare("INSERT into withdrawals set date_created= ?, user_id= ?, amount= ?, description= ?");
			$sql->bind_param("sids",  $_POST['date_created'],$_POST['user_id'], $_POST['amount'], $_POST['description']);
			$sql->execute();			
			//$sql = "INSERT INTO `withdrawals` set {$data} ";
			//$save = $this->conn->query($sql);
		}else{
			$sql = $this->conn->prepare("UPDATE withdrawals set active=?, date_created= ?, user_id= ?, amount= ?, description= ? where id = '{$id}'");
			$sql->bind_param("ssids", $_POST['active'], $_POST['date_created'],$_POST['user_id'], $_POST['amount'], $_POST['description']);
			$sql->execute();
			//$sql = "UPDATE `withdrawals` set {$data} where id = '{$id}' ";
			//$save = $this->conn->query($sql);
		}
		if($sql){ //dating $save
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Record successfully saved.");
			else
				$this->settings->set_flashdata('success',"Record successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}

	function save_loading1(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v = addslashes($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		//check if userid is empty
		if(empty($user_id)){
			$resp['status'] = 'failed';
			$resp['msg'] = " Invalid agent!";
			return json_encode($resp);
			exit;
		}
		
		//check if amount is zero
		if($amount <= 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Invalid amount!";
			return json_encode($resp);
			exit;			
		}
		
		//checkif password is correct
		$checkamt = $this->conn->query("SELECT * FROM `users` where id='{$agent_id}' and password = '{$agent_code}'")->num_rows; 
		if($checkamt <= 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Invalid Account!";
			return json_encode($resp);
			exit;
		}
		
		//checkif amount of parent is sufficient
		$checkamt = $this->conn->query("SELECT * FROM `users` where id='{$agent_id}' and amount < '{$_POST['amount']}'")->num_rows; 
		if($checkamt > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Insufficient balance";
			return json_encode($resp);
			exit;
		}
		//checkif user is active
		$checkactive = $this->conn->query("SELECT * FROM `users` where id='{$user_id}' and active='N'")->num_rows; 
		if($checkactive > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " User is Inactive";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			//sql bind parameters
			$sql = $this->conn->prepare("INSERT into loading set active='N',date_created=now(), user_id= ?, amount= ?, description= ?,agent_id=?");
			$sql->bind_param("idsi", $_POST['user_id'], $_POST['amount'], $_POST['description'],$_POST['agent_id']);
			$sql->execute();

			$sql1 = $this->conn->prepare("UPDATE users set amount=amount+? where id='{$_POST['user_id']}' ");
			$sql1->bind_param("d",$_POST['amount']);
			$sql1->execute();

			//ibawas kay parent ung amount

			$sql2 = $this->conn->prepare("UPDATE users set amount=amount-? where id=? ");
			$sql2->bind_param("di",$_POST['amount'],$_POST['agent_id']);
			$sql2->execute();
		
			$this->conn->query("commit");

		}
		if($sql){ //dating $save
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Record successfully saved.");
			else
				$this->settings->set_flashdata('success',"Record successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}

	function save_withdrawals1(){
		// extract($_POST);
		// //$agentid=$_settings->userdata('id');
		// $data = "";
		// foreach($_POST as $k =>$v){
		// 	if(!in_array($k,array('id'))){
		// 		$v = addslashes($v);
		// 		if(!empty($data)) $data .=",";
		// 		$data .= " `{$k}`='{$v}' ";
		// 	}
		// }

		$cleaned_post = [];
		$prefix = "cashout-";
		$prefix_length = strlen($prefix);

		foreach ($_POST as $key => $value) {
			if (substr($key, 0, $prefix_length) === $prefix) {
				$new_key = substr($key, $prefix_length);
				$cleaned_post[$new_key] = $value;
			} else {
				$cleaned_post[$key] = $value;
			}
		}
		$_POST = $cleaned_post; 
    	extract($_POST);

		//check if userid is empty
		if(empty($user_id)){
			$resp['status'] = 'failed';
			$resp['msg'] = " Invalid agent!";
			return json_encode($resp);
			exit;
		}
		//check if amount is zero
		if($amount <= 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Invalid amount!";
			return json_encode($resp);
			exit;			
		}
		//checkif password is correct
		$checkamt = $this->conn->query("SELECT * FROM `users` where id='{$_POST['agent_id']}' and password = '{$agent_code}'")->num_rows; 
		if($checkamt <= 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Invalid Account!";
			return json_encode($resp);
			exit;
		}
		//checkif amount if sufficient
		$checkamt = $this->conn->query("SELECT * FROM `users` where id ='{$_POST['user_id']}' and amount < '{$_POST['amount']}'")->num_rows; 
		if($checkamt > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Insufficient balance";
			return json_encode($resp);
			exit;
		}	
		//checkif user is active
		$checkactive = $this->conn->query("SELECT * FROM `users` where id='{$_POST['user_id']}' and active='N'")->num_rows; 
		if($checkactive > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " User is Inactive";
			return json_encode($resp);
			exit;
		}

		//checkif user is tagged to agent
		$checkagent = $this->conn->query("SELECT * FROM `users` where id='{$_POST['user_id']}' and parentid = '{$_POST['agent_id']}' ")->num_rows; 
		if($checkagent <= 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Invalid User!";
			return json_encode($resp);
			exit;
		}

		if(empty($id)){
			//sql bind parameters
			$sql = $this->conn->prepare("INSERT into withdrawals set active='N',date_created=now(), user_id= ?, amount= ?, description= ?,agent_id=?");
			$sql->bind_param("idsi",  $_POST['user_id'], $_POST['amount'], $_POST['description'], $_POST['agent_id']);
			$sql->execute();

			$sql1 = $this->conn->prepare("UPDATE users set amount=amount-? where id=? ");
			$sql1->bind_param("di",$_POST['amount'],$_POST['user_id']);
			$sql1->execute();

			//idagdag kay parent ung amount
			$sql2 = $this->conn->prepare("UPDATE users set amount=amount+? where id=? ");
			$sql2->bind_param("di",$_POST['amount'],$_POST['agent_id']);
			$sql2->execute();
			
			$this->conn->query("commit");
			
			
			$checkposted = $this->conn->query("SELECT * FROM `users` where id = '{$_POST['user_id']}'");
			if($checkposted->num_rows > 0){

				$row1 = $checkposted->fetch_assoc();

				//reset logs
				if ($row1['amount'] < 0)
				{

					//sql bind parameters
					$sql = $this->conn->prepare("INSERT into withdrawals set active='N',date_created=now(), user_id= ?, amount= -?, description= 'Refunded (Unusual withdrawal)',agent_id=?");
					$sql->bind_param("idi",  $_POST['user_id'], $_POST['amount'], $_POST['agent_id']);
					$sql->execute();

					$sql1 = $this->conn->prepare("UPDATE users set amount=amount+? where id=? ");
					$sql1->bind_param("di",$_POST['amount'],$_POST['user_id']);
					$sql1->execute();

					//idagdag kay parent ung amount
					$sql2 = $this->conn->prepare("UPDATE users set amount=amount-? where id=? ");
					$sql2->bind_param("di",$_POST['amount'],$_POST['agent_id']);
					$sql2->execute();
				
					$this->conn->query("commit");

				}

			}
			
		}
		if($sql){ //dating $save
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"Record successfully saved.");
			else
				$this->settings->set_flashdata('success',"Record successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_role(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `roles` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Record successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function delete_template(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `template` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Record successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function delete_events(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `events` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Record successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
 	function delete_commission(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `coms` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Record successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_withdrawals(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `withdrawals` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Record successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_loading(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `loading` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Record successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}


	function post_commission(){

		extract($_POST);

		$checkposted = $this->conn->query("SELECT * FROM `users` where id = '{$_POST['id']}' and com_amount_bal > 0 and com_amount_bal >= '{$_POST['convert_amount']}' ");
		if($checkposted->num_rows > 0){

			$row1 = $checkposted->fetch_assoc();

			$postw = $this->conn->query("UPDATE `users` set amount = amount + '{$_POST['convert_amount']}', com_amount_bal = com_amount_bal -'{$_POST['convert_amount']}' where id = '{$_POST['id']}' ");

			$post = $this->conn->query("INSERT INTO `coms_converted` set com_amount_accu = '{$row1['com_amount_accu']}', com_amount_bal = '{$row1['com_amount_bal']}', amount_converted = '{$_POST['convert_amount']}', user_id = '{$_POST['id']}', agent_id = '{$_POST['agentid']}' "); 

			if($post){
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success',"Record successfully posted.");
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = $this->conn->error;
			}

			//reset logs
			if (($row1['com_amount_bal'] - $_POST['convert_amount']) == 0)
			{

			$postr = $this->conn->query("UPDATE `coms` set active = 'N', agent_id = '{$_POST['agentid']}', description = 'converted to wallet' where active = 'Y' and user_id = '{$_POST['id']}' ");
	
			}


		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "Please check converted amount.";

		}


		return json_encode($resp);

	}

	function post_withdrawals(){
		extract($_POST);

		$checkwith = $this->conn->query("SELECT * FROM `withdrawals` where `id` = '{$id}' and active = 'N' ")->num_rows; //check if this record is asctive
		if($checkwith > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Record already posted";
			return json_encode($resp);
			exit;
		}

		$checkamt = $this->conn->query("SELECT * FROM `users` where id ='{$userid}' and amount < '{$amount}'")->num_rows; //checkif amount if sufficient
		if($checkamt > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Insufficient balance";
			return json_encode($resp);
			exit;
		}
	
		$postwith= $this->conn->query("UPDATE `users` set amount = amount - '{$amount}' where id = '{$userid}'"); //ilagay din ung amount sa wallet nya
		$postwi = $this->conn->query("UPDATE `withdrawals` set active ='N' where id = '{$id}'");

		if($postwi){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Record successfully posted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function post_loading(){
		extract($_POST);

		$checkwith = $this->conn->query("SELECT * FROM `loading` where `id` = '{$id}' and active = 'N' ")->num_rows; //check if this record is active
		if($checkwith > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Record already posted";
			return json_encode($resp);
			exit;
		}

		$checkamt = $this->conn->query("SELECT * FROM `users` where id = (SELECT parentid from `users` where id ='{$userid}') and amount < '{$amount}'")->num_rows; //checkif amount of parent is sufficient
		if($checkamt > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Insufficient balance";
			return json_encode($resp);
			exit;
		}
	
		//ibawas kay parent ung amount
		$checkload = $this->conn->query("SELECT * FROM `users` where id = (SELECT parentid from `users` where id ='{$userid}')");
		if($checkload->num_rows > 0){
			while($row = $checkload->fetch_assoc()) {
			$postload= $this->conn->query("UPDATE `users` set amount = amount - '{$amount}' where id  ='{$row['id']}' "); //ibawas kay parent ung amount
			}
		}
		
		$postwith= $this->conn->query("UPDATE `users` set amount = amount + '{$amount}' where id = '{$userid}'"); //idagdag kay userid ung amount
		$postwi = $this->conn->query("UPDATE `loading` set active ='N' where id = '{$id}'");

		if($postwi){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Record successfully posted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function batchpost_commission(){
		extract($_POST);
		//if($userid =='*'){
			
		//}
		$batch = $this->conn->query("SELECT * FROM `coms` where active = 'Y' and user_id ='{$userid}'");
		if($batch->num_rows > 0){		
			while($row = $batch->fetch_assoc()) {
				$postuser = $this->conn->query("UPDATE `users` set amount = amount + '{$row['amount']}' where id = '{$row['user_id']}'"); 
				$postcoms = $this->conn->query("UPDATE `coms` set active ='N' where id = '{$row['id']}'");	
			}
		}
		if($batch->num_rows > 0){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Records successfully posted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function postall_commission(){
		extract($_POST);
		$postbatch = $this->conn->query("SELECT * FROM `coms` where active = 'Y'");
		if($postbatch->num_rows > 0){		
			while($row = $postbatch->fetch_assoc()) {
				$postuser = $this->conn->query("UPDATE `users` set amount = amount + '{$row['amount']}' where id = '{$row['user_id']}'"); 
			}			
		}
		$postcoms = $this->conn->query("UPDATE `coms` set active ='N' where active ='Y'");
		if($postcoms){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Records successfully posted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function postall_withdrawals(){
		$postbatch = $this->conn->query("SELECT * FROM `withdrawals` where active = 'Y'");
		if($postbatch->num_rows > 0){		
			while($row = $postbatch->fetch_assoc()) {
				$up = $this->conn->query("UPDATE `withdrawals` set active ='N' where id = '{$row['id']}' and amount <= (select amount from `users` where id = '{$row['user_id']}')");
				$postwithuser = $this->conn->query("UPDATE `users` set amount = amount - '{$row['amount']}' where id = '{$row['user_id']}'  and amount >= '{$row['amount']}'"); 

			}			
		}

		if($postbatch->num_rows > 0){ 
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Records successfully posted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function postall_load(){
		$postbatch = $this->conn->query("SELECT * FROM `loading` where active = 'Y'");
		if($postbatch->num_rows > 0){		
			while($row = $postbatch->fetch_assoc()) {

				$x = $this->conn->query("SELECT * FROM `users` where id = (SELECT parentid from `users` where id ='{$row['user_id']}')");
				if($x->num_rows > 0){
					$row1 = $x->fetch_assoc();
					if($row1['amount']  >= $row['amount'] ){
						$up = $this->conn->query("UPDATE `loading` set active ='N' where id = '{$row['id']}'"); 
						$updateparent = $this->conn->query("UPDATE `users` set amount = amount - '{$row['amount']}' where id = '{$row1['id']}' "); //para sa parent
						$updatechild = $this->conn->query("UPDATE `users` set amount = amount + '{$row['amount']}' where id = '{$row['user_id']}' "); //para sa chile
					}
				}
			}		
		}

		if($postbatch->num_rows > 0){ 
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Records successfully posted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function generate_string($input, $strength = 10) {
		
		$input_length = strlen($input);
		$random_string = '';
		for($i = 0; $i < $strength; $i++) {
			$random_character = $input[mt_rand(0, $input_length - 1)];
			$random_string .= $random_character;
		}
	 
		return $random_string;
	}


	function delete_img(){
		extract($_POST);
		if(is_file(base_app.$path)){
			if(unlink(base_app.$path)){
				$del = $this->conn->query("DELETE FROM `uploads` where file_path = '{$path}'");
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}

	function save_bets(){

		extract($_POST);

		//pag wlang active draw
		//if(empty($drawid)){
		//	$resp['status'] = 'failed';
		//	$resp['msg'] = " NO ACTIVE DRAW!";
		//	return json_encode($resp);
		//	exit;
		//}

		$event = $_POST['eventid'];

		$check_close_draw = $this->conn->query("SELECT id FROM `draws` where eventid = '{$event}' and `active` = 'Y' order by id desc limit 1 ");
		if($check_close_draw->num_rows > 0){
		
			//check if fight is closed
			$check_close = $this->conn->query("SELECT id FROM `draws` where eventid = '{$event}' and `active` = 'Y' and status = '3' order by id desc limit 1 "); //lagyan ng staus = !open !lastcall
			if($check_close->num_rows > 0){
				$resp['status'] = 'failed';
				$resp['msg'] = " FIGHT IS CLOSE!";
				return json_encode($resp);
				exit;			
			}

		}else
		{

			$resp['status'] = 'failed';
			$resp['msg'] = " NO ACTIVE DRAW!";
			return json_encode($resp);
			exit;	
		}	
	

		//check sufficient balance
		$check_bal = $this->conn->query("SELECT amount,type FROM `users` where `id` = '{$user_id}' ");
		if($this->capture_err())
			return $this->capture_err();
		if($check_bal->num_rows > 0){
			$row = $check_bal->fetch_assoc();

			//para hindi makapagbit ang agent
	
				if ($row['type'] == '2' ) {
					$resp['status'] = 'failed';
					$resp['msg'] = " AGENT NOT ALLOWED TO BET!";
					return json_encode($resp);
					exit;
				}
		

			//para pa lang sa red amount ito
			if ($_POST['betid']=='1'){	
				if ($row['amount']< $red_amount ) {
					$resp['status'] = 'failed';
					$resp['msg'] = " INSUFFICIENT BALANCE!";
					return json_encode($resp);
					exit;
				}
			}
			//para pa lang sa blue amount ito
			if ($_POST['betid']=='2'){	
				if ($row['amount']< $blue_amount ) {
					$resp['status'] = 'failed';
					$resp['msg'] = " INSUFFICIENT BALANCE!";
					return json_encode($resp);
					exit;
				}
			}
			//para pa lang sa yellow amount ito
			if ($_POST['betid']=='3'){	
				if ($row['amount']< $yellow_amount ) {
					$resp['status'] = 'failed';
					$resp['msg'] = " INSUFFICIENT BALANCE!";
					return json_encode($resp);
					exit;
				}
			}
			
		}else{
			//walang record sa my_balance table
			$resp['status'] = 'failed';
			$resp['msg'] = " DEACTIVATED ACCOUNT!";
			return json_encode($resp);
			exit;

		}
		//minimum bet
		if ($_POST['betid']=='1'){
			if ($_POST['red_amount']<10 ) {
				$resp['status'] = 'failed';
				$resp['msg'] = " INSUFFICIENT BET";
				return json_encode($resp);
				exit;
			}			
		}
		if ($_POST['betid']=='2'){
			if ($_POST['blue_amount']<10 ) {
				$resp['status'] = 'failed';
				$resp['msg'] = " INSUFFICIENT BET";
				return json_encode($resp);
				exit;
			}			
		}
		if ($_POST['betid']=='3'){
			if ($_POST['yellow_amount']<10 ) {
				$resp['status'] = 'failed';
				$resp['msg'] = " INSUFFICIENT BET";
				return json_encode($resp);
				exit;
			}			
		}

		//check maximum bet 50000
		$check_max = $this->conn->query("SELECT red_amount, blue_amount, yellow_amount FROM `bets` where drawid = (Select id from draws where eventid = '{$event}' and `active` = 'Y' order by id desc limit 1) and user_id = '{$_POST['user_id']}' "); //lagyan ng staus = !open !lastcall
		if($check_max->num_rows > 0){
				$row = $check_max->fetch_assoc();

				if( ($row['red_amount'] + $_POST['red_amount']) > 5000000 or ($row['blue_amount'] + $_POST['blue_amount']) > 5000000 or ($row['yellow_amount'] + $_POST['yellow_amount']) > 5000000 ){
					$resp['status'] = 'failed';
					$resp['msg'] = " MAXIMUM BET REACHED!";
					return json_encode($resp);
					exit;	
				}
		
		}else{
			if( $_POST['red_amount'] > 5000000 or $_POST['blue_amount'] > 5000000 or $_POST['yellow_amount'] > 5000000 ){
				$resp['status'] = 'failed';
				$resp['msg'] = " MAXIMUM BET REACHED!";
				return json_encode($resp);
				exit;	
			}			
		}

		if(empty($id)){

		    //drawid
		    $drawqry = $this->conn->query("SELECT id, (select odds from template where game_id = (select game_id from events where id = '{$event}')) odds from draws where eventid = '{$event}' and active = 'Y' order by id desc limit 1 ");
			if($drawqry->num_rows > 0){
				$drow = $drawqry->fetch_assoc();
				$drawid = $drow['id'];
				$odds = $drow['odds'];
			}

			try {
			
			$this->conn->query("set autocommit=0");
			$this->conn->query("start transaction");

			//sql bind parameters
			$sql = $this->conn->prepare("INSERT into bets set drawid =?, user_id=?, date_created= ?, red_amount= ?, blue_amount= ?, yellow_amount= ? ");
			$sql->bind_param("iisddd", $drawid,$user_id, $date_created, $red_amount, $blue_amount, $yellow_amount);
			$sql->execute();


			//$sql = "INSERT INTO `bets` set drawid='{$drawid}', user_id='{$user_id}',date_created='{$date_created}',red_amount ='{$red_amount}', blue_amount='{$blue_amount}',yellow_amount='{$yellow_amount}' ";
			//$save = $this->conn->query($sql);

			//dito ilagay ung payout percentage
			
			//sql bind parameters
			$sql1 = $this->conn->prepare("UPDATE draws set red=red+?, blue=blue+?, yellow=yellow+? where id='{$drawid}' ");
			$sql1->bind_param("ddd",$red_amount,$blue_amount,$yellow_amount);
			$sql1->execute();

			//$sql1 = "UPDATE draws set red = (red + '{$red_amount}'),blue = (blue + '{$blue_amount}'),yellow = (yellow + '{$yellow_amount}') where id='{$drawid}' ";			
			//$save1 = $this->conn->query($sql1);

			//wag na baguhin wlang userinput
			$sql5 = "UPDATE draws set red_payout = COALESCE(((red+blue) * '{$odds}' )/NULLIF(red, 0), 0.00), blue_payout = COALESCE(((red+blue) * '{$odds}')/NULLIF(blue, 0), 0.00) where id='{$drawid}' ";		
			$save5 = $this->conn->query($sql5);

			//sql bind parameters
			$sql2 = $this->conn->prepare("UPDATE users set amount =amount-(?+?+?) where id='{$user_id}' ");
			$sql2->bind_param("ddd",$red_amount,$blue_amount,$yellow_amount);
			$sql2->execute();

			//$sql2 = "UPDATE users set amount = (amount - ('{$red_amount}'+'{$blue_amount}'+'{$yellow_amount}')) where id='{$user_id}' ";
			//$save2 = $this->conn->query($sql2);

			$this->conn->query("commit");
			$this->conn->query("set autocommit=1");

			} catch (Exception $e) {

				$this->conn->query("rollback");
				$this->conn->query("set autocommit=1");
				$resp['status'] = 'failed';
				$resp['msg'] = $e->getMessage();
				return json_encode($resp);
				exit;

			}

	

		}else{


		    //drawid
		    	$drawqry = $this->conn->query("SELECT id, (select odds from template where game_id = (select game_id from events where id = '{$event}')) odds from draws where eventid = '{$event}' and active ='Y' order by id desc limit 1 ");
			if($drawqry->num_rows > 0){
				$drow = $drawqry->fetch_assoc();
				// $drawid = $drow['id'];
				$odds = $drow['odds'];
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = " NO ACTIVE DRAW!";
				return json_encode($resp);
				exit;
			}

			try {
			
			$this->conn->query("set autocommit=0");
			$this->conn->query("start transaction");

			//sql bind parameters			
			$sql = $this->conn->prepare("UPDATE bets set red_amount = red_amount+?, blue_amount=blue_amount+?, yellow_amount=yellow_amount+? where id = '{$id}' ");
			$sql->bind_param("ddd", $red_amount, $blue_amount, $yellow_amount);
			$sql->execute();

			//$sql = "UPDATE `bets` set red_amount = (red_amount + '{$red_amount}'), blue_amount = (blue_amount + '{$blue_amount}'),yellow_amount = (yellow_amount + '{$yellow_amount}') where id = '{$id}' ";
			//$save = $this->conn->query($sql); 
			
			//sql bind parameters
			$sql1 = $this->conn->prepare("UPDATE draws set red=red+?, blue=blue+?, yellow=yellow+? where id='{$drawid}'");
			$sql1->bind_param("ddd",$red_amount,$blue_amount,$yellow_amount);
			$sql1->execute();

			//$sql1 = "UPDATE draws set red = (red + '{$red_amount}'),blue = (blue + '{$blue_amount}'),yellow = (yellow + '{$yellow_amount}') where id='{$drawid}' ";
			//$save1 = $this->conn->query($sql1);

			$sql5 = "UPDATE draws set red_payout = COALESCE(((red+blue) * '{$odds}')/NULLIF(red, 0), 0.00), blue_payout = COALESCE(((red+blue) * '{$odds}')/NULLIF(blue, 0), 0.00) where id='{$drawid}' ";		
			$save5 = $this->conn->query($sql5);


			//sql bind parameters
			$sql2 = $this->conn->prepare("UPDATE users set amount =amount-(?+?+?) where id='{$user_id}' ");
			$sql2->bind_param("ddd",$red_amount,$blue_amount,$yellow_amount);
			$sql2->execute();
			//$sql2 = "UPDATE users set amount = amount - ('{$red_amount}'+'{$blue_amount}'+'{$yellow_amount}') where id='{$user_id}' ";
			//$save2 = $this->conn->query($sql2);

			$this->conn->query("commit");
			$this->conn->query("set autocommit=1");

			} catch (Exception $e) {

				$this->conn->query("rollback");
				$this->conn->query("set autocommit=1");
				$resp['status'] = 'failed';
				$resp['msg'] = $e->getMessage();
				return json_encode($resp);
				exit;

			}

		}

		if($sql){
			$resp['status'] = 'success';
			//$this->settings->set_flashdata('success',"BET SUCCESSFULLY PLACED!");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function finish_transaction(){


		//$debugging = $this->conn->query("UPDATE `debugging` set remarks = 'Im here' WHERE id = 32076");

		extract($_POST);

		$checkevent = $this->conn->query("SELECT id FROM `events` where game_id = '{$_POST['game_id']}' and active = 'Y' "); //check if there is an active event
		if($checkevent ->num_rows > 0){
			$rowevent = $checkevent->fetch_assoc();
			$event = $rowevent['id']; 
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = " No active EVENT!";
			return json_encode($resp);
			exit;
		}//end of checking

		$check = $this->conn->query("SELECT * FROM `draws` where eventid = '{$event}' and `active` = 'Y' order by id desc limit 1 "); //lagyan ng staus = !open !lastcall
		if($check->num_rows > 0){
			$row = $check->fetch_assoc();
			


			if($_POST['winner']=='1' or $_POST['winner']=='2'){
				if($row['blue'] <= 0 or $row['red']<= 0){ //check if there is zero total bets
				$resp['status'] = 'failed';
				$resp['msg'] = " PAYOUT ODDS IS INVALID!";
				return json_encode($resp);
				exit;
				}
			}

					$this->close_status($event);

					$qry = $this->conn->query("SELECT * FROM `bets` WHERE drawid = '{$row['id']}' and active = 'Y' "); //select all bets on the current draw

					if($qry->num_rows > 0){


							while($row1 = $qry->fetch_assoc()) {

								try {

								$this->conn->query("set autocommit=0");
								$this->conn->query("start transaction");


								if (($_POST['winner']) =='1'){

									$earnings ="UPDATE bets set active = 'N', earnings=earnings + ('{$row['red_payout']}'    * '{$row1['red_amount']}') where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount +     ('{$row['red_payout']}'    * '{$row1['red_amount']}')           where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);

								}elseif  (($_POST['winner']) =='2') {
									$earnings ="UPDATE bets set active = 'N', earnings=earnings + ('{$row['blue_payout']}'   * '{$row1['blue_amount']}')  where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount +     ('{$row['blue_payout']}'   * '{$row1['blue_amount']}')         where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);

								}elseif (($_POST['winner']) =='3') {
									$earnings ="UPDATE bets set active = 'N', earnings=earnings +  (('{$row1['yellow_amount']}' * 8) + '{$row1['red_amount']}' +  '{$row1['blue_amount']}')   where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount + (('{$row1['yellow_amount']}' * 8) + '{$row1['red_amount']}' +  '{$row1['blue_amount']}') where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);

								}else { //CANCEL LANG

									$earnings ="UPDATE bets set active = 'N', earnings=earnings + '{$row1['red_amount']}' + '{$row1['blue_amount']}' + '{$row1['yellow_amount']}' where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount + ('{$row1['red_amount']}' +  '{$row1['blue_amount']}' +  '{$row1['yellow_amount']}' ) where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);	
								}


									if (($_POST['winner']) =='1' or ($_POST['winner']) =='2'){


									//updating of coms
									$coms = $this->conn->query("SELECT X,T2.id,T2.rate,T2.active
									FROM (
									SELECT @x := @x+ 1 as X,
									@r AS _id,
									(SELECT @r := parentid FROM users WHERE id = _id) AS parentid
									FROM
									(SELECT @r := '{$row1['user_id']}') vars,
									users h,(select @x := -1) i
									WHERE @r <> 0) T1
									JOIN users T2
									ON T1._id = T2.id
									where T2.rate > 0 and T2.active = 'Y' and T2.id != '{$row1['user_id']}'
									group by T2.id,T2.rate,T2.active order by X asc");


									$oldrate=0;
									$newrate=0;
									$com_amount=0;

										if($coms->num_rows > 0){
											while($row2 = $coms->fetch_assoc()) {

												if(   ($row2['rate'] - $oldrate) > 0 ){

													$newrate = $row2['rate'] - $oldrate;
													$com_amount = (($row2['rate'] - $oldrate)/100) * ($row1['red_amount'] + $row1['blue_amount']);

													$sql2 = "UPDATE `users` set com_amount_accu = com_amount_accu + '{$com_amount}', com_amount_bal = com_amount_bal + '{$com_amount}' where `id` = '{$row2['id']}' ";
													$save2 = $this->conn->query($sql2);

													$sql3 = "INSERT INTO `coms` set user_id = '{$row2['id']}', player_id = '{$row1['user_id']}',com_rate = '{$newrate}', amount = '{$com_amount}', date_created= now(), drawid = '{$row['id']}',eventid = '{$event}' ";
													$save3 = $this->conn->query($sql3);

													$oldrate = $row2['rate'];

												}

											}//end while
										}
									}

								//end coms

								// commit the transaction

								$this->conn->query("commit");
								$this->conn->query("set autocommit=1");
								

								} catch (Exception $e) {

									$this->conn->query("rollback");
									$this->conn->query("set autocommit=1");
									$resp['status'] = 'failed';
									$resp['msg'] = $e->getMessage();
									return json_encode($resp);
									exit;

								}


							}//end while


							$sqlT = "UPDATE `draws`
							set `draws`.active = 'N',
							`draws`.winner = '{$_POST['winner']}'
							where `draws`.eventid = '{$event}' and `draws`.active = 'Y'
							and `draws`.id not in (SELECT drawid FROM `bets` WHERE drawid = `draws`.id and active = 'Y')";
							$saveT = $this->conn->query($sqlT);

							$this->conn->query("commit");
							$this->conn->query("set autocommit=1");

							$resp['status'] = 'success';


					} else { //end numrows kung walang bet sa active draw, e di walang gagawin

							$sqlT = "UPDATE `draws`
							set `draws`.active = 'N',
							`draws`.winner = '{$_POST['winner']}'
							where `draws`.eventid = '{$event}' and `draws`.active = 'Y'
							and `draws`.id not in (SELECT drawid FROM `bets` WHERE drawid = `draws`.id and active = 'Y')";
							$saveT = $this->conn->query($sqlT);

							$this->conn->query("commit");
							$this->conn->query("set autocommit=1");

							$resp['status'] = 'success';

					}

		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = " NO ACTIVE FIGHT!";
			return json_encode($resp);
			exit;
		}

		return json_encode($resp);

	}

	function redeclare_transaction(){


		//$debugging = $this->conn->query("UPDATE `debugging` set remarks = 'Im here' WHERE id = 32076");

		extract($_POST);


			//$resp['status'] = 'failed';
			//$resp['msg'] = $_POST['winner'];
			//return json_encode($resp);
			//exit;

		$event = $_POST['eventid'];

		$checkevent = $this->conn->query("SELECT * FROM `draws` where eventid = '{$event}' and `active` = 'Y' order by id desc limit 1"); //check if there is an active event
		if($checkevent ->num_rows > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " ACTIVE FIGHT!";
			return json_encode($resp);
			exit;
		}//end of checking



		$check = $this->conn->query("SELECT * FROM `draws` where eventid = '{$event}' and `active` = 'N' and id = '{$_POST['drawid']}' order by id desc limit 1 "); //lagyan ng staus = !open !lastcall
		if($check->num_rows > 0){

			$row = $check->fetch_assoc();
			
				if($row['blue'] <= 0 or $row['red']<= 0){ //check if there is zero total bets
				$resp['status'] = 'failed';
				$resp['msg'] = " PAYOUT ODDS IS INVALID!";
				return json_encode($resp);
				exit;
				}

				if($_POST['winner'] == $row['winner']){ //check if there is zero total bets
					$resp['status'] = 'failed';
					$resp['msg'] = " WINNER ALREADY DECLARED!";
					return json_encode($resp);
					exit;
				}

					$this->close_status($event);

					$qry = $this->conn->query("SELECT * FROM `bets` WHERE drawid = '{$row['id']}'"); //select all bets on the current draw

					if($qry->num_rows > 0){

							while($row1 = $qry->fetch_assoc()) {
								try {

								$this->conn->query("set autocommit=0");
								$this->conn->query("start transaction");

								//ROLLBACK
								if (($row['winner']) =='1'){

									$earnings ="UPDATE bets set active = 'N', earnings = earnings - ('{$row['red_payout']}'    * '{$row1['red_amount']}') where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount -     ('{$row['red_payout']}'    * '{$row1['red_amount']}')           where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);

								}elseif  (($row['winner']) =='2') {
									$earnings ="UPDATE bets set active = 'N', earnings=earnings - ('{$row['blue_payout']}'   * '{$row1['blue_amount']}')  where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount -     ('{$row['blue_payout']}'   * '{$row1['blue_amount']}')        where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);

								}elseif (($row['winner']) =='3') {

									$earnings ="UPDATE bets set active = 'N', earnings = earnings -  (('{$row1['yellow_amount']}' * 8) + '{$row1['red_amount']}' +  '{$row1['blue_amount']}')   where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE users set amount = amount - (('{$row1['yellow_amount']}' * 8) + '{$row1['red_amount']}' +  '{$row1['blue_amount']}') where id = '{$row1['user_id']}'";
									$save1 = $this->conn->query($sql1);

								}elseif (($row['winner']) =='4') {

									$earnings ="UPDATE bets set active = 'N', earnings = earnings - ('{$row1['red_amount']}' + '{$row1['blue_amount']}' + '{$row1['yellow_amount']}') where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount - ('{$row1['red_amount']}' +  '{$row1['blue_amount']}' +  '{$row1['yellow_amount']}') where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);	
								}

									if (($row['winner']) =='1' or ($row['winner']) =='2'){

									//updating of coms
									$coms = $this->conn->query("SELECT X,T2.id,T2.rate,T2.active
									FROM (
									SELECT @x := @x+ 1 as X,
									@r AS _id,
									(SELECT @r := parentid FROM users WHERE id = _id) AS parentid
									FROM
									(SELECT @r := '{$row1['user_id']}') vars,
									users h,(select @x := -1) i
									WHERE @r <> 0) T1
									JOIN users T2
									ON T1._id = T2.id
									where T2.rate > 0 and T2.active = 'Y' and T2.id != '{$row1['user_id']}'
									group by T2.id,T2.rate,T2.active order by X asc");


									$oldrate=0;
									$newrate=0;
									$com_amount=0;

										if($coms->num_rows > 0){
											while($row2 = $coms->fetch_assoc()) {

												if(   ($row2['rate'] - $oldrate) > 0 ){

													$newrate = $row2['rate'] - $oldrate;
													$com_amount = (($row2['rate'] - $oldrate)/100) * ($row1['red_amount'] + $row1['blue_amount']);

													$sql2 = "UPDATE `users` set com_amount_accu = com_amount_accu - '{$com_amount}', com_amount_bal = com_amount_bal - '{$com_amount}' where `id` = '{$row2['id']}' ";
													$save2 = $this->conn->query($sql2);

													$sql3 = "INSERT INTO `coms` set user_id = '{$row2['id']}', player_id = '{$row1['user_id']}',com_rate = '{$newrate}', amount = '{$com_amount}' * -1, date_created= now(), drawid = '{$row['id']}',eventid = '{$row['eventid']}' ";
													$save3 = $this->conn->query($sql3);

													$oldrate = $row2['rate'];

												}

											}//end while
										}
									}

								//end coms


								$this->conn->query("commit");
								$this->conn->query("set autocommit=1");
								$this->conn->query("commit");

								} catch (Exception $e) {
									$this->conn->query("set autocommit=1");
									$this->conn->query("rollback");
									$resp['status'] = 'failed';
									$resp['msg'] = $e->getMessage();
									return json_encode($resp);
									exit;

								}


								try {

								$this->conn->query("set autocommit=0");
								$this->conn->query("start transaction");

								//REDECLARE
								if (($_POST['winner']) =='1'){

									$earnings ="UPDATE bets set active = 'N', earnings = earnings + ('{$row['red_payout']}'    * '{$row1['red_amount']}') where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount +     ('{$row['red_payout']}'    * '{$row1['red_amount']}')           where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);

								}elseif  (($_POST['winner']) =='2') {
									$earnings ="UPDATE bets set active = 'N', earnings = earnings + ('{$row['blue_payout']}'   * '{$row1['blue_amount']}')  where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount +     ('{$row['blue_payout']}'   * '{$row1['blue_amount']}')         where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);

								}elseif (($_POST['winner']) =='3') {
									$earnings ="UPDATE bets set active = 'N', earnings = earnings +  (('{$row1['yellow_amount']}' * 8) + '{$row1['red_amount']}' +  '{$row1['blue_amount']}')   where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount + (('{$row1['yellow_amount']}' * 8) + '{$row1['red_amount']}' +  '{$row1['blue_amount']}') where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);

								}elseif (($_POST['winner']) =='4') { 

									$earnings ="UPDATE bets set active = 'N', earnings = earnings + '{$row1['red_amount']}' + '{$row1['blue_amount']}' + '{$row1['yellow_amount']}' where id ='{$row1['id']}'";
									$saveearnings = $this->conn->query($earnings);

									$sql1 = "UPDATE `users` set amount = amount + ('{$row1['red_amount']}' +  '{$row1['blue_amount']}' +  '{$row1['yellow_amount']}') where id = '{$row1['user_id']}' ";
									$save1 = $this->conn->query($sql1);	
								}


									if (($_POST['winner']) =='1' or ($_POST['winner']) =='2'){


									//updating of coms
									$coms = $this->conn->query("SELECT X,T2.id,T2.rate,T2.active
									FROM (
									SELECT @x := @x+ 1 as X,
									@r AS _id,
									(SELECT @r := parentid FROM users WHERE id = _id) AS parentid
									FROM
									(SELECT @r := '{$row1['user_id']}') vars,
									users h,(select @x := -1) i
									WHERE @r <> 0) T1
									JOIN users T2
									ON T1._id = T2.id
									where T2.rate > 0 and T2.active = 'Y' and T2.id != '{$row1['user_id']}'
									group by T2.id,T2.rate,T2.active order by X asc");


									$oldrate=0;
									$newrate=0;
									$com_amount=0;

										if($coms->num_rows > 0){
											while($row2 = $coms->fetch_assoc()) {

												if(   ($row2['rate'] - $oldrate) > 0 ){

													$newrate = $row2['rate'] - $oldrate;
													$com_amount = (($row2['rate'] - $oldrate)/100) * ($row1['red_amount'] + $row1['blue_amount']);

													$sql2 = "UPDATE `users` set com_amount_accu = com_amount_accu + '{$com_amount}', com_amount_bal = com_amount_bal + '{$com_amount}' where `id` = '{$row2['id']}' ";
													$save2 = $this->conn->query($sql2);

													$sql3 = "INSERT INTO `coms` set user_id = '{$row2['id']}', player_id = '{$row1['user_id']}',com_rate = '{$newrate}', amount = '{$com_amount}', date_created= now(), drawid = '{$row['id']}',eventid = '{$row['eventid']}' ";
													$save3 = $this->conn->query($sql3);

													$oldrate = $row2['rate'];

												}

											}//end while
										}
									}

								//end coms

								// commit the transaction


								$this->conn->query("commit");
								$this->conn->query("set autocommit=1");
								$this->conn->query("commit");

								} catch (Exception $e) {
									$this->conn->query("set autocommit=1");
									$this->conn->query("rollback");
									$resp['status'] = 'failed';
									$resp['msg'] = $e->getMessage();
									return json_encode($resp);
									exit;

								}


							}//end while

							$sqlT = "UPDATE `draws`	set active = 'N', winner = '{$_POST['winner']}', is_Redeclared=1,datetime_Redeclared=now() where id = '{$_POST['drawid']}'";
							$saveT = $this->conn->query($sqlT);


							$this->conn->query("commit");
							$this->conn->query("set autocommit=1");

							$resp['status'] = 'success';


					} else {
							$sqlT = "UPDATE `draws`	set active = 'N', winner = '{$_POST['winner']}', is_Redeclared=1,datetime_Redeclared=now() where id = '{$_POST['drawid']}'";
							$saveT = $this->conn->query($sqlT);

							$this->conn->query("commit");
							$this->conn->query("set autocommit=1");
							$resp['status'] = 'success';

					}

		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = " FIGHT NOT FOUND!";
			return json_encode($resp);
			exit;
		}

		return json_encode($resp);

	}

	function new_transaction(){
		extract($_POST);
		$check_existing = $this->conn->query("SELECT * FROM `draws` where eventid = '{$_POST['eventid']}' and `active` = 'Y' ")->num_rows;
		if($check_existing > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " EXISTING ACTIVE FIGHT!";
			return json_encode($resp);
			exit;
		}

		if ($eventid ==0){
			$resp['status'] = 'failed';
			$resp['msg'] = " NO ACTIVE EVENT!";
			return json_encode($resp);
			exit;			
		}
			
		$sql ="INSERT INTO `draws` set eventid = '{$eventid}', drawno= '{$drawno}', user_id= '{$user_id}', date_created = '{$date_created}' ";
		$save = $this->conn->query($sql);
		if($sql){
			$resp['status'] = 'success';
			//$this->settings->set_flashdata('success'," FIGHT SAVED.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	
	function update_status(){
		extract($_POST);
		$check_active = $this->conn->query("SELECT * FROM `draws` where eventid = '{$_POST['eventid']}' and `active` = 'Y' order by id desc limit 1 "); //lagyan ng staus = !open !lastcall
		if($check_active->num_rows > 0){
			$sql = "UPDATE `draws` set status = '{$_POST['status']}' where eventid = '{$_POST['eventid']}' and active = 'Y' ";
			$save = $this->conn->query($sql);

			if($sql){
				$resp['status'] = 'success';
				//$this->settings->set_flashdata('success'," FIGHT STATUS UPDATED!");
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = $this->conn->error;
			}			
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = " NO ACTIVE FIGHT!";
			return json_encode($resp);
			exit;
		}

		return json_encode($resp);
	}


	function close_status($event){
	
			$sql = "UPDATE `draws` set status = '3' where eventid = '{$event}' and active = 'Y'";
			$save = $this->conn->query($sql);

	}


}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_role':
		echo $Master->save_role();
	break;
	case 'delete_role':
		echo $Master->delete_role();
	break;
	case 'save_commission':
		echo $Master->save_commission();
	break;
	case 'save_withdrawals':
		echo $Master->save_withdrawals();
	break;
	case 'save_withdrawals1':
		echo $Master->save_withdrawals1();
	break;
	case 'save_loading':
		echo $Master->save_loading();
	break;
	case 'save_loading1':
		echo $Master->save_loading1();
	break;
	case 'delete_commission':
		echo $Master->delete_commission();
	break;
	case 'delete_withdrawals':
		echo $Master->delete_withdrawals();
	break;
	case 'delete_loading':
		echo $Master->delete_loading();
	break;
	case 'post_commission':
		echo $Master->post_commission();
	break;
	case 'post_withdrawals':
		echo $Master->post_withdrawals();
	break;
	case 'post_loading':
		echo $Master->post_loading();
	break;
	case 'batchpost_commission':
		echo $Master->batchpost_commission();
	break;
	case 'postall_commission':
		echo $Master->postall_commission();
	break;
	case 'postall_withdrawals':
		echo $Master->postall_withdrawals();
	break;
	case 'postall_load':
		echo $Master->postall_load();
	break;
	case 'save_template':
		echo $Master->save_template();
	break;
	case 'save_events':
		echo $Master->save_events();
	break;
	case 'save_bets':
		echo $Master->save_bets();
	break;
	case 'delete_template':
		echo $Master->delete_template();
	break;
	case 'delete_events':
		echo $Master->delete_events();
	break;
	case 'save_transaction':
		echo $Master->save_transaction();
	break;
	case 'delete_transaction':
		echo $Master->delete_transaction();
	break;
	case 'receive_transaction':
		echo $Master->receive_transaction();
	break;
	case 'forward_transaction':
		echo $Master->forward_transaction();
	break;
	case 'recall_transaction':
		echo $Master->recall_transaction();
	break;
	case 'finish_transaction':
		echo $Master->finish_transaction();
	break;
	case 'redeclare_transaction':
		echo $Master->redeclare_transaction();
	break;
	case 'new_transaction':
		echo $Master->new_transaction();
	break;
	case 'update_status':
		echo $Master->update_status();
	break;
	case 'delete_img':
		echo $Master->delete_img();
	break;


	default:
		// echo $sysset->index();
		break;
}