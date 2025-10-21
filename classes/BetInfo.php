<?php
require_once('../config.php');

class BetInfo {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }
    

    // AJAX Handling

    public function getCommWithdrawHistoryOfUserAjax($userId, $ending_asof, $ending_amount){
        $stmt = $this->conn->prepare("
            select '{$ending_asof}' date_created, '{$ending_amount}' amount, 0 as type, '' accttyp, '' processby
            UNION ALL
            select date_created,amount,1 as type,
            (select
            concat(case
                when role = 1 then 'Financer Account'
                when role = 2 then 'Operator'
                when role = 3 then 'Sub-Operator'
                when role = 4 then 'Master Agent'
                when role = 5 then 'Player'
                else ''
            end,' - ', username)
            from users where id = a.agent_id) accttyp,
            (select username from users where id = a.agent_id) processby from loading a where active ='N' and user_id = '{$userId}' and date_created >= '{$ending_asof}'
            UNION ALL
            select date_created,amount,2 as type,
            (select
            concat(case
                when role = 1 then 'Financer Account'
                when role = 2 then 'Operator'
                when role = 3 then 'Sub-Operator'
                when role = 4 then 'Master Agent'
                when role = 5 then 'Player'
                else ''
            end,' - ', username)
            from users where id = a.agent_id) accttyp,
            (select username from users where id = a.agent_id) processby from withdrawals a where active ='N' and user_id = '{$userId}' and date_created >= '{$ending_asof}'
            UNION ALL
            select date_created,amount,5 as type,
            (select
            concat(case
                when role = 1 then 'Financer Account'
                when role = 2 then 'Operator'
                when role = 3 then 'Sub-Operator'
                when role = 4 then 'Master Agent'
                when role = 5 then 'Player'
                else ''
            end,' - ', username)
            from users where id = a.user_id) accttyp,
            (select username from users where id = a.agent_id) processby from loading a where active ='N' and agent_id = '{$userId}' and date_created >= '{$ending_asof}'
            UNION ALL
            select date_created,amount,6 as type,
            (select
            concat(case
                when role = 1 then 'Financer Account'
                when role = 2 then 'Operator'
                when role = 3 then 'Sub-Operator'
                when role = 4 then 'Master Agent'
                when role = 5 then 'Player'
                else ''
            end,' - ', username)
            from users where id = a.user_id) accttyp,
            (select username from users where id = a.agent_id) processby from withdrawals a where active ='N' and agent_id = '{$userId}' and date_created >= '{$ending_asof}'

            UNION ALL

            select date_created, amount_converted as amount,3  as type,
            (select
            concat(case
                when role = 1 then 'Financer Account'
                when role = 2 then 'Operator'
                when role = 3 then 'Sub-Operator'
                when role = 4 then 'Master Agent'
                when role = 5 then 'Player'
                else ''
            end,' - ', username)
            from users where id = a.agent_id) accttyp,
            (select username from users where id = a.agent_id) processby from coms_converted a where a.user_id = '{$userId}' and a.date_created >= '{$ending_asof}'
            
            UNION ALL

            select date_created, (earnings-red_amount-blue_amount-yellow_amount) as amount, 4 as type,concat((select drawno from draws where id = a.drawid),'-', (select name from events where id = (select eventid from draws where id = a.drawid))) accttyp,'' processby from bets a where user_id = '{$userId}' and date_created >= '{$ending_asof}'
            order by date_created asc, type
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
        
        case 'get_comm_withdraw_history_of_user':
            if(isset($_POST['user_id'])){
                $userId = $_POST['user_id'];
                $ending_asof = $_POST['ending_asof'];
                $amount = $_POST['amount'];
                $resp = $betInfo->getCommWithdrawHistoryOfUserAjax($userId,$ending_asof,$amount);
            } else {
                $resp = ['status' => 'failed', 'err' => 'Missing user_id parameter.'];
            }
            break;
        
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