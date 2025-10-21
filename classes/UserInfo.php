<?php
require_once('../config.php');

class UserInfo {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserInfo($userId) {
        $qry = $this->conn->query("SELECT * from users where id ='{$userId}' ");
        $row = $qry->fetch_assoc();
        return $row;
    }

    public function getAllAgentsUnderAgent($userId,$type) {
        if ($type == 1){ //use admin priv
            $qry = $this->conn->query("SELECT * from `users` where type = 2  and active in ('Y','N','F','T')");
        }else{
            $qry = $this->conn->query("SELECT * from `users` where type = 2  and active in ('Y','N','F','T') and parentid = '{$userId}' order by username asc ");
        }
        
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllPlayersUnderAgent($userId,$type) {
        if ($type == 1){ //use admin priv
            $qry = $this->conn->query("SELECT * from `users` where type = 3  and active in ('Y','N','F','T')");
        }else{
            $qry = $this->conn->query("SELECT * from `users` where type = 3  and active in ('Y','N','F','T') and parentid = '{$userId}' order by username asc ");
        }
        
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    // AJAX Handling

    public function getUserData($userId){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row) {
            return ['status' => 'success', 'content' => $row]; // Return the data as a PHP array
        } else {
            return ['status' => 'failed', 'content' => 'User not found.'];
        }
    }
}

// AJAX Request

// Check if a function ('f') is specified in the URL query string
if(isset($_GET['f'])){
    // Instantiate the class with the global variables from config.php
    $userInfo = new UserInfo($conn);
    $functionName = $_GET['f'];
    $resp = array();

    switch($functionName){
        
        case 'get_user_data':
            // Check for the required POST parameter
            if(isset($_POST['user_id'])){
                $userId = $_POST['user_id'];
                // Call the correct method
                $resp = $userInfo->getUserData($userId);
            } else {
                $resp = ['status' => 'failed', 'err' => 'Missing user_id parameter.'];
            }
            break;
        // You can add more AJAX functions here if needed
        default:
            $resp = ['status' => 'failed', 'err' => 'Function not found.'];
            break;
    }

    // Set the content type header and output the JSON response
    header('Content-Type: application/json');
    echo json_encode($resp);
    exit; // Stop execution after sending the response
}