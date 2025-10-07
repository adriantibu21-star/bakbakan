<?php
require_once('../config.php');
//require_once('./generatecode.php');
function random_strings($length_of_string) 
{ 
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz'; 
    return substr(str_shuffle($str_result), 0, $length_of_string); 
} 

// This function will generate 
// Random string of length 10 //echo random_strings(10); 
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		extract($_POST);
		$data = '';

		//if (preg_match('/^(.{0,7}|[^a-z]*|[^\d]*)$/i', $_POST['password'])) {
		//	return 3;
		//	exit;
		 // }

		$chk = $this->conn->query("SELECT * FROM `users` where username ='{$username}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}

		//	$sql = "INSERT INTO `transactions` set office2 ='{$_POST['source']}',  {$data}  ";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','password'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],'../'. $fname);
				if($move){
					$data .=" , avatar = '{$fname}' ";
					if(isset($_SESSION['userdata']['avatar']) && is_file('../'.$_SESSION['userdata']['avatar']) && $_SESSION['userdata']['id'] == $id)
						unlink('../'.$_SESSION['userdata']['avatar']);
				}
		}
		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			$last_id = $this->conn->insert_id;
			$ref = $last_id.random_strings(20);
			$qry1 = $this->conn->query("UPDATE users set refcode= '{$ref}' where id = '{$last_id}'");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully saved.');
				return 1;
			}else{
				return 2;
			}

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				return 1;
			}else{
				return "UPDATE users set $data where id = {$id}";

			}

		}
	}
	public function register_users(){
		extract($_POST);
		$data = '';
		$id='';

		$chk = $this->conn->query("SELECT * FROM `users` where username ='{$username}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}

		//	$sql = "INSERT INTO `transactions` set office2 ='{$_POST['source']}',  {$data}  ";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','password'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}
			$qry = $this->conn->query("INSERT INTO users set type='3', role='5', {$data}");
			$last_id = $this->conn->insert_id;
			$ref = $last_id.random_strings(20);
			$qry1 = $this->conn->query("UPDATE users set refcode= '{$ref}' where id = '{$last_id}'");

			if($qry){
				$this->settings->set_flashdata('success','User Details successfully saved.');
				return 1;
			}else{
				return 2;
			}
	}

	public function save_fusers(){
		extract($_POST);
		$data = '';

		//if (preg_match('/^(.{0,7}|[^a-z]*|[^\d]*)$/i', $_POST['password'])) {
		//	return 3;
		//	exit;
		 // }

		$chk = $this->conn->query("SELECT * FROM `agents` where username ='{$username}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','password'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],'../'. $fname);
				if($move){
					$data .=" , avatar = '{$fname}' ";
					if(isset($_SESSION['userdata']['avatar']) && is_file('../'.$_SESSION['userdata']['avatar']) && $_SESSION['userdata']['id'] == $id)
						unlink('../'.$_SESSION['userdata']['avatar']);
				}
		}
		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO agents set {$data}");
			if($qry){
				$this->settings->set_flashdata('success','Agent Details successfully saved.');
				return 1;
			}else{
				return 2;
			}

		}else{
			$qry = $this->conn->query("UPDATE agents set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','Agent Details successfully updated.');
				return 1;
			}else{
				return "UPDATE agents set $data where id = {$id}";

			}

		}
	}

	public function delete_users(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM users where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM users where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			if(is_file(base_app.$avatar))
				unlink(base_app.$avatar);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}

	public function activate_users(){
		extract($_POST);
		$qry = $this->conn->query("UPDATE users set active = 'Y' where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','User successfully activated.');
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}

	public function convert_user(){
		extract($_POST);
		$qry = $this->conn->query("UPDATE users set role = '{$role}', type = 2, rate = '{$rate}' where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','User successfully updated to agent.');
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	public function deactivate_users(){
		extract($_POST);
		$qry = $this->conn->query("UPDATE users set active = 'N' where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','User successfully deactivated.');
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	public function delete_fusers(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM agents where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM agents where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','Agent Details successfully deleted.');
			if(is_file(base_app.$avatar))
				unlink(base_app.$avatar);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}

	
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'register':
		echo $users->register_users();
	break;
	case 'convert':
		echo $users->convert_user();
	break;
	case 'fsave':
		echo $users->save_fusers();
	break;
	case 'delete':
		echo $users->delete_users();
	case 'activate':
			echo $users->activate_users();
	break;
	case 'deactivate':
		echo $users->deactivate_users();
break;
	case 'fdelete':
		echo $users->delete_fusers();
	break;
	default:
		// echo $sysset->index();
		break;
}