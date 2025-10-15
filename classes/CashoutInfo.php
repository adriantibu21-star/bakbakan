<?php
require_once('../config.php');

class CashoutInfo {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCashoutHistoryOfUser($userId) {
        $qry = $this->conn->query("SELECT * from loading where user_id ='{$userId}' ");
        $row = $qry->fetch_assoc();
        return $row;
    }

    // AJAX Handling

    public function getCashoutHistoryOfUserAjax($userId){
        $stmt = $this->conn->prepare("
            SELECT
                withdrawalRec.*,
                userRec.username as user_username,
                userRec.amount as user_amount,
                agentRec.username as agent_username
            FROM 
                withdrawals withdrawalRec
            LEFT JOIN 
                users agentRec ON withdrawalRec.agent_id = agentRec.id
            LEFT JOIN 
                users userRec ON withdrawalRec.user_id = userRec.id
            WHERE 
                withdrawalRec.user_id = ? 
            ORDER BY 
                withdrawalRec.date_created DESC
        ");
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
    
    $cashoutInfo = new CashoutInfo($conn);
    $functionName = $_GET['f'];
    $resp = array();

    switch($functionName){
        
        case 'get_cashout_history_of_user':
            if(isset($_POST['user_id'])){
                $userId = $_POST['user_id'];
                $resp = $cashoutInfo->getCashoutHistoryOfUserAjax($userId);
            } else {
                $resp = ['status' => 'failed', 'err' => 'Missing user_id parameter.'];
            }
            break;
            
        default:
            $resp = ['status' => 'failed', 'err' => 'Function not found.'];
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($resp);
    exit;
}