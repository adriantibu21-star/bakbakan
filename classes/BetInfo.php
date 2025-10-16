<?php
require_once('../config.php');

class BetInfo {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }
    

    // AJAX Handling

    public function getBetHistoryOfUserAjax($userId){
        $stmt = $this->conn->prepare("
            SELECT
                date_created,
                amount,
                0.00 AS red_amount,
                0.00 AS blue_amount,
                0.00 AS yellow_amount,
                1 AS type,
                '' AS winner,
                '' AS drawno
            FROM
                loading a
            WHERE
                active = 'N' AND user_id = '{$userId}'

            UNION

            SELECT
                date_created,
                amount,
                0.00 AS red_amount,
                0.00 AS blue_amount,
                0.00 AS yellow_amount,
                2 AS type,
                '' AS winner,
                '' AS drawno
            FROM
                withdrawals a
            WHERE
                active = 'N' AND user_id = '{$userId}'

            UNION

            SELECT
                date_created,
                amount,
                0.00 AS red_amount,
                0.00 AS blue_amount,
                0.00 AS yellow_amount,
                3 AS type,
                '' AS winner,
                '' AS drawno
            FROM
                coms a
            WHERE
                active = 'N' AND user_id = '{$userId}'

            UNION

            SELECT
                date_created,
                (earnings - red_amount - blue_amount - yellow_amount) AS amount,
                red_amount,
                blue_amount,
                yellow_amount,
                4 AS type,
                (SELECT winner FROM draws WHERE id = a.drawid) AS winner,
                (
                    SELECT
                        CONCAT(a.drawno, ' - ', b.name)
                    FROM
                        draws a
                    LEFT JOIN
                        events b ON b.id = a.eventid
                    WHERE
                        a.id = a.drawid
                ) AS drawno
            FROM
                bets a
            WHERE
                user_id = '{$userId}'

            ORDER BY
                date_created ASC;
        ");
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

    public function getInitialBalanceOfUser($userId){
        $stmt = $this->conn->prepare("
            SELECT
                T1.ending_asof,
                T1.amount,
                T1.type
            FROM
                (
                    SELECT
                        ending_asof,
                        amount,
                        'Cut-off Balance' AS type
                    FROM
                        endingbalance
                    WHERE
                        user_id = '{$userId}'

                    UNION

                    SELECT
                        DATE_FORMAT(date_added, '%Y-%m-%d %H:%i') AS ending_asof, -- Alias date to match first SELECT
                        0 AS amount,
                        'Beginning Balance' AS type
                    FROM
                        users
                    WHERE
                        id = '{$userId}'
                    ORDER BY
                        ending_asof DESC
                    LIMIT 1
                ) AS T1;
        ");
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
    
    $betInfo = new BetInfo($conn);
    $functionName = $_GET['f'];
    $resp = array();

    switch($functionName){
        
        case 'get_bet_history_of_user':
            if(isset($_POST['user_id'])){
                $userId = $_POST['user_id'];
                $resp = $betInfo->getBetHistoryOfUserAjax($userId);
            } else {
                $resp = ['status' => 'failed', 'err' => 'Missing user_id parameter.'];
            }
            break;
            
        
        case 'get_initial_balance_of_user':
            if(isset($_POST['user_id'])){
                $userId = $_POST['user_id'];
                $resp = $betInfo->getInitialBalanceOfUser($userId);
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