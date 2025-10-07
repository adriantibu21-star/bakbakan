<?php
require_once '../config.php';

class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	public function login(){
		//$_SESSION['gab'] = 'gab'; //dito ung session

		//$_SESSION["login_time_stamp"] = time();
		
		//session_start();
		//print("Old: ".session_id());
		//echo "<br>";
		//if (!isset($_SESSION['start'])) {
    	//$_SESSION['start'] = time();
		//} elseif (time() - $_SESSION['start'] > 1800) {
    	//session_regenerate_id(true); 
    	//$_SESSION['start'] = time(); 
		//}
		//print("Now: ".session_id());

		extract($_POST);

		$user = $username;
		$password = md5($password);
	 
		$sql = $this->conn->prepare("SELECT * FROM users WHERE username = ? AND password = ? and active = 'Y' ");
		$sql->bind_param('ss', $user, $password);
		$sql->execute();
		$qry = $sql->get_result();
		//$qry = $this->conn->query("SELECT * from users where username = '$username' and password = md5('$password') and active = 'Y' ");

		if($qry->num_rows > 0){
			foreach($qry->fetch_array() as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}

			}
			$this->settings->set_userdata('login_type',1);
			// add active event to session
			$arena = $this->conn->query("SELECT * FROM events where active = 'Y'");
			if($arena->num_rows > 0){
				foreach($arena->fetch_array() as $k =>$v){
				if(!is_numeric($k)){
				$this->settings->set_userdata('event_'.$k,$v);
				}
				}
			}
		//dito maglagay ng sessionid
		return json_encode(array('status'=>'success'));
		}else{
		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = md5('$password') "));
		}
	}

	public function flogin(){
		extract($_POST);

		$qry = $this->conn->query("SELECT * from agents where username = '$username' and password = md5('$password') ");
		if($qry->num_rows > 0){
			foreach($qry->fetch_array() as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}

			}
			$this->settings->set_userdata('login_type',2);
		return json_encode(array('status'=>'success'));
		}else{
		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from agents where username = '$username' and password = md5('$password') "));
		}
	}
	public function logout(){
		session_destroy();
		if($this->settings->sess_des()){
			redirect('index.php');
		}
	}
	public function flogout(){
		if($this->settings->sess_des()){
			redirect('index.php');
		}
	}
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'logout':
		echo $auth->logout();
		break;
		case 'flogin':
			echo $auth->flogin();
			break;
		case 'flogout':
			echo $auth->flogout();
			break;
	default:
		echo $auth->index();
		break;
}

