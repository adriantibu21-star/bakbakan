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
            $qry = $this->conn->query("SELECT * from `users` where type in (2,4)  and active in ('Y','N','F','T')");
        }else{
            $qry = $this->conn->query("SELECT * from `users` where type = 2  and active in ('Y','N','F','T') and parentid = '{$userId}' order by username asc ");
        }
        
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllPlayersUnderAgent($userId,$type) {
        if ($type == 1){ //use admin priv
            $qry = $this->conn->query("SELECT u.*, agent.username AS agent_username FROM users AS u INNER JOIN users AS agent ON agent.id = u.parentid WHERE u.type = 3 AND u.active IN ('Y','N','F','T');");
        }else{
            $qry = $this->conn->query("    SELECT u.*,  agent.username AS agent_username FROM users AS u INNER JOIN users AS agent ON agent.id = u.parentid WHERE u.type = 3 AND u.active IN ('Y','N','F','T') AND u.parentid = '{$userId}' ORDER BY u.username ASC");
        }
        
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    public function getApprovalPlayersUnderAgent($userId,$type) {
        if ($type == 1){ //use admin priv
            $qry = $this->conn->query("SELECT * from `users` where type = 3  and active in ('N','F')");
        }else{
            $qry = $this->conn->query("SELECT * from `users` where type = 3  and active in ('N','F') and parentid = '{$userId}' order by username asc ");
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

    public function getAllAgentsUnderAgentAjax($userId) {
        $stmt = $this->conn->prepare("SELECT * from `users` where type = 2  and active in ('Y','N','F','T') and parentid = ? order by username asc ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        if (!empty($rows)) {
            return ['status' => 'success', 'content' => $rows];
        } else {
            return ['status' => 'success', 'content' => []];
        }
    }

    public function getAllPlayersUnderAgentAjax($userId) {
        $stmt = $this->conn->prepare("SELECT * from `users` where type = 3  and active in ('Y','N','F','T') and parentid = ? order by username asc ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        if (!empty($rows)) {
            return ['status' => 'success', 'content' => $rows];
        } else {
            return ['status' => 'success', 'content' => []];
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
        
        case 'get_all_agents_under_agent':
            // Check for the required POST parameter
            if(isset($_POST['user_id'])){
                $userId = $_POST['user_id'];
                // Call the correct method
                $resp = $userInfo->getAllAgentsUnderAgentAjax($userId);
            } else {
                $resp = ['status' => 'failed', 'err' => 'Missing user_id parameter.'];
            }
            break;
        
        case 'get_all_players_under_agent':
            // Check for the required POST parameter
            if(isset($_POST['user_id'])){
                $userId = $_POST['user_id'];
                // Call the correct method
                $resp = $userInfo->getAllPlayersUnderAgentAjax($userId);
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