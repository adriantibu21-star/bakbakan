<?php
require_once('../config.php');

class BetInfo {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }
    

    // AJAX Handling


    public function getBetHistoryOfAgentAjax($userId){
       $ending_asof = '1900-01-01'; 
        $ending_amount = 0.00;
        $type_name = 'Unknown';
        
        $qryBeginingbalance_sql = "
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
                        user_id = ?

                    UNION

                    SELECT
                        DATE_FORMAT(date_added, '%Y-%m-%d %H:%i') AS ending_asof,
                        0 AS amount,
                        'Beginning Balance' AS type
                    FROM
                        users
                    WHERE
                        id = ?
                    ORDER BY
                        ending_asof DESC
                    LIMIT 1
                ) AS T1;
        ";

        $stmt_bal = $this->conn->prepare($qryBeginingbalance_sql);

        $stmt_bal->bind_param("ss", $userId, $userId);

        $stmt_bal->execute();
        $result_bal = $stmt_bal->get_result();

        if ($result_bal->num_rows > 0) {
            $rowevent = $result_bal->fetch_assoc();
            $ending_asof = $rowevent['ending_asof']; 
            $ending_amount = $rowevent['amount'];
            $type_name = $rowevent['type']; 
        }
        $stmt_bal->close();
        
        $sql = "
            ( -- Beginning Balance
                SELECT 
                    ? AS date_created, 
                    ? AS amount, 
                    0 AS type, 
                    ? AS accttyp, 
                    '' AS processby
            )
            UNION ALL
            ( -- Cash-In (type 1) - user is being credited
                SELECT 
                    a.date_created,
                    a.amount,
                    1 AS type,
                    CONCAT(
                        (SELECT 
                            CASE 
                                WHEN role = 1 THEN 'Financer Account'
                                WHEN role = 2 THEN 'Operator'
                                WHEN role = 3 THEN 'Sub-Operator'
                                WHEN role = 4 THEN 'Master Agent'
                                WHEN role = 5 THEN 'Player'
                                ELSE ''
                            END
                        FROM users WHERE id = a.agent_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.agent_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby
                FROM loading a
                WHERE a.active = 'N' AND a.user_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- Cash-Out (type 2) - user is being debited
                SELECT 
                    a.date_created,
                    a.amount,
                    2 AS type,
                    CONCAT(
                        (SELECT 
                            CASE 
                                WHEN role = 1 THEN 'Financer Account'
                                WHEN role = 2 THEN 'Operator'
                                WHEN role = 3 THEN 'Sub-Operator'
                                WHEN role = 4 THEN 'Master Agent'
                                WHEN role = 5 THEN 'Player'
                                ELSE ''
                            END
                        FROM users WHERE id = a.agent_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.agent_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby
                FROM withdrawals a
                WHERE a.active = 'N' AND a.user_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- Cash-In (Downline) (type 5) - user is debited (acts as agent for downline load)
                SELECT 
                    a.date_created,
                    a.amount,
                    5 AS type,
                    CONCAT(
                        (SELECT 
                            CASE 
                                WHEN role = 1 THEN 'Financer Account'
                                WHEN role = 2 THEN 'Operator'
                                WHEN role = 3 THEN 'Sub-Operator'
                                WHEN role = 4 THEN 'Master Agent'
                                WHEN role = 5 THEN 'Player'
                                ELSE ''
                            END
                        FROM users WHERE id = a.user_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.user_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby
                FROM loading a
                WHERE a.active = 'N' AND a.agent_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- Cash-Out (Downline) (type 6) - user is credited (acts as agent for downline withdrawal)
                SELECT 
                    a.date_created,
                    a.amount,
                    6 AS type,
                    CONCAT(
                        (SELECT 
                            CASE 
                                WHEN role = 1 THEN 'Financer Account'
                                WHEN role = 2 THEN 'Operator'
                                WHEN role = 3 THEN 'Sub-Operator'
                                WHEN role = 4 THEN 'Master Agent'
                                WHEN role = 5 THEN 'Player'
                                ELSE ''
                            END
                        FROM users WHERE id = a.user_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.user_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby
                FROM withdrawals a
                WHERE a.active = 'N' AND a.agent_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- Commission (type 3) - user is being credited
                SELECT 
                    a.date_created, 
                    a.amount_converted AS amount, 
                    3 AS type,
                    CONCAT(
                        (SELECT 
                            CASE 
                                WHEN role = 1 THEN 'Financer Account'
                                WHEN role = 2 THEN 'Operator'
                                WHEN role = 3 THEN 'Sub-Operator'
                                WHEN role = 4 THEN 'Master Agent'
                                WHEN role = 5 THEN 'Player'
                                ELSE ''
                            END
                        FROM users WHERE id = a.agent_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.agent_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby 
                FROM coms_converted a 
                WHERE a.user_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- Winnings/Bets (type 4) - user's net amount from a bet (can be positive for win, negative for loss)
                SELECT 
                    a.date_created, 
                    (a.earnings - a.red_amount - a.blue_amount - a.yellow_amount) AS amount, 
                    4 AS type,
                    CONCAT(
                        (SELECT drawno FROM draws WHERE id = a.drawid),
                        '-', 
                        (SELECT name FROM events WHERE id = (SELECT eventid FROM draws WHERE id = a.drawid))
                    ) AS accttyp,
                    '' AS processby 
                FROM bets a 
                WHERE a.user_id = ? AND a.date_created >= ?
            )
            ORDER BY date_created ASC, type
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "sds" . "ss" . "ss" . "ss" . "ss" . "ss" . "ss", 
            $ending_asof, 
            $ending_amount, 
            $type_name, 
            $userId, $ending_asof, 
            $userId, $ending_asof, 
            $userId, $ending_asof, 
            $userId, $ending_asof, 
            $userId, $ending_asof, 
            $userId, $ending_asof 
        );

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        $rows = [];
        $bal = 0.00;
        $i = 1;

        while ($row = $result->fetch_assoc()) {
            $row['display_amount'] = $row['amount']; // Initialize display amount
            $transaction_type_name = 'Winnings/Bets'; // Default name (matches the last 'else')

            switch ($row['type']) {
                case 0: // Beginning Balance
                    $bal += $row['amount'];
                    $transaction_type_name = $type_name;
                    break;
                case 1: // Cash-In (Credit)
                    $bal += $row['amount'];
                    $transaction_type_name = 'Cash-In';
                    break;
                case 2: // Cash-Out (Debit)
                    $bal -= $row['amount'];
                    $row['display_amount'] = '-' . number_format($row['amount'], 2); // Show as negative
                    $transaction_type_name = 'Cash-Out';
                    break;
                case 3: // Commission (Credit)
                    $bal += $row['amount'];
                    $transaction_type_name = 'Commission';
                    break;
                case 4: // Winnings/Bets (Credit/Debit) - Amount is already calculated as net (earnings - bets)
                    $bal += $row['amount'];
                    $transaction_type_name = 'Winnings/Bets';
                    break;
                case 5: // Cash-In (Downline) (Debit) - Agent is debited
                    $bal -= $row['amount'];
                    $row['display_amount'] = '-' . number_format($row['amount'], 2); // Show as negative
                    $transaction_type_name = 'Cash-In (Downline)';
                    break;
                case 6: // Cash-Out (Downline) (Credit) - Agent is credited
                    $bal += $row['amount'];
                    $transaction_type_name = 'Cash-Out (Downline)';
                    break;
            }
            
            $row['row_num'] = $i++;
            $row['current_balance'] = $bal;
            $row['transaction_type_name'] = $transaction_type_name;
            $rows[] = $row;
        }

        if (!empty($rows)) {
            return ['status' => 'success', 'content' => $rows];
        } else {
            return ['status' => 'success', 'content' => []];
        }
    }
    
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
        
        case 'get_bet_history_of_agent':
            if(isset($_POST['user_id'])){
                $userId = $_POST['user_id'];
                $resp = $betInfo->getBetHistoryOfAgentAjax($userId);
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