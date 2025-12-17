<?php
require_once('../config.php');

class CashinInfo {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCashinHistoryOfUser($userId) {
        $qry = $this->conn->query("SELECT * from loading where user_id ='{$userId}' ");
        $row = $qry->fetch_assoc();
        return $row;
    }

    // AJAX Handling

    public function getCashinHistoryOfUserAjax($userId){
        $stmt = $this->conn->prepare("
            SELECT
                loadingRec.*,
                userRec.username as user_username,
                userRec.amount as user_amount,
                agentRec.username as agent_username
            FROM 
                loading loadingRec
            LEFT JOIN 
                users agentRec ON loadingRec.agent_id = agentRec.id
            LEFT JOIN 
                users userRec ON loadingRec.user_id = userRec.id
            WHERE 
                loadingRec.user_id = ? 
            ORDER BY 
                loadingRec.date_created DESC
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


if(isset($_GET['f'])){
    
    $cashinInfo = new CashinInfo($conn);
    $functionName = $_GET['f'];
    $resp = array();

    switch($functionName){
        
        case 'get_cashin_history_of_user':
            if(isset($_POST['user_id'])){
                $userId = $_POST['user_id'];
                $resp = $cashinInfo->getCashinHistoryOfUserAjax($userId);
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