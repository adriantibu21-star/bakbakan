<?php
require_once('../config.php');

class BetInfo {
    private $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }
    

    public function getLoadLogsOfAgent($userId){
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
        
        return $rows;

    }

    public function getCommLogsOfAgent($userId) {
        // 1. Get the agent's current commission balance for the starting point
        $initial_balance_sql = "
            SELECT com_amount_bal
            FROM users
            WHERE id = ?
        ";
        $stmt_bal = $this->conn->prepare($initial_balance_sql);
        $stmt_bal->bind_param("i", $userId);
        $stmt_bal->execute();
        $result_bal = $stmt_bal->get_result();
        // Initialize with the current balance, or 0.00 if user not found
        $initial_balance = $result_bal->fetch_assoc()['com_amount_bal'] ?? 0.00;
        $stmt_bal->close();

        // 2. Combine all commission and withdrawal transactions
        $sql = "
            SELECT
                t.`DATE`,
                t.`FIGHT#`,
                t.`EVENT`,
                t.`USERNAME`,
                t.`AGENT`,
                t.`BET`,
                t.`COMMISSION`,
                t.`WITHDRAWAL`,
                t.`%EARN`
            FROM
                (
                    -- Commission Transactions (CREDIT)
                    SELECT
                        a.date_created AS `DATE`,
                        d.drawno AS `FIGHT#`,
                        e.name AS `EVENT`,
                        p.username AS `USERNAME`,
                        ag.username AS `AGENT`,
                        a.blue_amount + a.red_amount + a.yellow_amount AS `BET`,
                        b.amount AS `COMMISSION`,
                        0.00 AS `WITHDRAWAL`,
                        CONCAT(
                            b.com_rate,
                            '% ',
                            (SELECT
                                CASE
                                    WHEN p.parentid = ag.id THEN 'Earn direct player'
                                    WHEN ag.type = 1 THEN 'Earn Operator'
                                    WHEN ag.type = 2 THEN 'Earn Sub-Operator'
                                    WHEN ag.type = 3 THEN 'Earn Master Agent'
                                    WHEN ag.type = 4 THEN 'Earn Gold Agent'
                                    WHEN ag.type = 5 THEN 'Earn Player'
                                    ELSE ''
                                END
                            FROM users WHERE id = b.user_id)
                        ) AS `%EARN`
                    FROM bets a
                    INNER JOIN coms b ON b.drawid = a.drawid AND b.player_id = a.user_id
                    INNER JOIN users ag ON ag.id = b.user_id   
                    INNER JOIN users p ON p.id = a.user_id     
                    INNER JOIN draws d ON d.id = a.drawid
                    INNER JOIN events e ON e.id = d.eventid
                    WHERE b.user_id = ? AND b.active = 'Y'

                    UNION ALL

                    -- Withdrawal Transactions (DEBIT)
                    SELECT
                        w.date_created AS `DATE`,
                        NULL AS `FIGHT#`,
                        'Withdrawal' AS `EVENT`,
                        ag.username AS `USERNAME`, 
                        ag.username AS `AGENT`,     
                        0.00 AS `BET`,
                        0.00 AS `COMMISSION`,
                        w.amount_converted AS `WITHDRAWAL`, 
                        'Withdrawal' AS `%EARN`
                    --    that debit the commission balance
                    FROM coms_converted w 
                    INNER JOIN users ag ON ag.id = w.user_id
                    WHERE w.user_id = ?
                ) t
            -- Order oldest to newest for PHP reverse calculation
            ORDER BY t.`DATE` ASC, t.`FIGHT#` ASC; 
        ";

        $stmt = $this->conn->prepare($sql);
        // Bind $userId twice for the UNION ALL query
        $stmt->bind_param("ii", $userId, $userId); 
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        // 3. PHP Running Balance Calculation and Formatting (FIXED)
        
        // Start with the agent's TRUE current balance (e.g., $153.09)
        $current_balance = (float) $initial_balance; 
        
        // Fetch all, reverse to process from newest to oldest
        $all_transactions = $result->fetch_all(MYSQLI_ASSOC);
        $reversed_transactions = array_reverse($all_transactions);
        
        $processed_rows = [];

        foreach ($reversed_transactions as $row) {
            $commission = (float) $row['COMMISSION'];
            $withdrawal = (float) $row['WITHDRAWAL'];

            // 1. ASSIGN: Set the RUNNING_BALANCE to the current balance.
            $row['RUNNING_BALANCE'] = $current_balance;

            // 2. REVERSE CALCULATE: Determine the balance *before* this transaction occurred.
            $current_balance -= $commission; // Reverse Credit
            $current_balance += $withdrawal; // Reverse Debit

            // --- Formatting for Display (NEW LOGIC) ---
            
            // If it's a WITHDRAWAL, display the amount as a negative number
            if ($withdrawal > 0.00) {
                 // Format the withdrawal amount with a minus sign for the COMMISSION column display
                 $row['COMMISSION_formatted'] = '-' . number_format($withdrawal, 2);
            } 
            // If it's a COMMISSION, display the positive amount
            else {
                 $row['COMMISSION_formatted'] = number_format($commission, 2);
            }
            
            // Other formatting (kept the same)
            $row['DATE_formatted'] = date("M-d-Y H:i:s", strtotime($row['DATE']));
            $row['BET_formatted'] = number_format((float)$row['BET'], 2);
            $row['WITHDRAWAL_formatted'] = number_format($withdrawal, 2);
            $row['RUNNING_BALANCE_formatted'] = number_format($row['RUNNING_BALANCE'], 2);

            $processed_rows[] = $row;
        }

        return $processed_rows;
    }

    public function getCommWithdrawalOfAgent($userId, $userType) {
        $user_id = $userId;
        $user_type = $userType;

        $agent_qry = $this->conn->query("SELECT id, username FROM users");
        $agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC), 'username', 'id');

        $sql = "
            SELECT 
                cc.id,
                cc.date_created,
                cc.user_id,
                cc.amount_converted,
                cc.agent_id
            FROM coms_converted cc
        ";

        if ($user_type == 1) {
            $sql .= " ORDER BY cc.id DESC";
        } 
        else {
            $sql .= "
                WHERE cc.user_id IN (SELECT id FROM users WHERE parentid = ? OR id = ?)
                ORDER BY cc.id DESC
            ";
        }

        $stmt = $this->conn->prepare($sql);

        if ($user_type != 1) {
            $stmt->bind_param("ii", $user_id, $user_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        $withdrawal_running_sum = 0; 
        $processed_rows = [];
        $i = 1; 

        while($row = $result->fetch_assoc()) {
            $withdrawal_running_sum += $row['amount_converted']; // Mimics $bal = $bal + amount
            
            $processed_rows[] = [
                'i'                  => $i++,
                'DATE'               => date("m-d-Y H:i:s", strtotime($row['date_created'])),
                'USER_USERNAME'      => $agent_arr[$row['user_id']] ?? 'N/A', // Look up user_id
                'AMOUNT_converted'   => number_format((float)$row['amount_converted'], 2),
                'AGENT_USERNAME'     => $agent_arr[$row['agent_id']] ?? 'N/A', // Look up agent_id
                'RUNNING_SUM'        => number_format($withdrawal_running_sum, 2)
            ];
        }

        return [
            'rows' => $processed_rows,
            'total_withdrawal_sum' => $withdrawal_running_sum // The final raw sum
        ];
    }

    public function getBetHistoryOfUser($userId)
    {
        // 1. Initialize variables and fetch the beginning balance
        $ending_asof = '1900-01-01'; 
        $ending_amount = 0.00;
        $type_name = 'Beginning Balance'; // Default
        
        $qryBeginingbalance_sql = "
            SELECT T1.ending_asof, T1.amount, T1.type 
            FROM (
                SELECT ending_asof, amount, 'Cut-off Balance' AS type FROM endingbalance WHERE user_id = ?
                UNION 
                SELECT DATE_FORMAT(date_added, '%Y-%m-%d %H:%i') AS ending_asof, 0 AS amount, 'Beginning Balance' AS type FROM users WHERE id = ?
                ORDER BY ending_asof DESC LIMIT 1
            ) AS T1;
        ";

        $stmt_bal = $this->conn->prepare($qryBeginingbalance_sql);
        $stmt_bal->bind_param("ss", $userId, $userId); 
        $stmt_bal->execute();
        $result_bal = $stmt_bal->get_result();

        if ($result_bal->num_rows > 0) {
            $rowevent = $result_bal->fetch_assoc();
            $ending_asof = $rowevent['ending_asof']; 
            $ending_amount = (float)$rowevent['amount']; // Cast to float
            $type_name = $rowevent['type'];
        }
        $stmt_bal->close();
        
        // 2. Prepare the main ledger history query
        // The SQL is structured to match the 'getBetHistoryOfAgent' function,
        // BUT INCLUDES the necessary fields for the client-side table rendering (winner, amounts, drawno).
        $sql = "
            ( -- TYPE 0: Beginning Balance / Cut-off
                SELECT 
                    ? AS date_created, ? AS amount, 
                    0 AS type, 
                    ? AS accttyp, 
                    '' AS processby,
                    0 AS winner, 
                    0.00 AS red_amount, 
                    0.00 AS blue_amount, 
                    0.00 AS yellow_amount, 
                    '' AS drawno,
                    '' AS bet_status 
            )
            UNION ALL
            ( -- TYPE 1: Cash-In (user is being credited)
                SELECT 
                    a.date_created, a.amount, 
                    1 AS type,
                    CONCAT(
                        (SELECT CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END FROM users WHERE id = a.agent_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.agent_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby,
                    0 AS winner, 
                    0.00 AS red_amount, 
                    0.00 AS blue_amount, 
                    0.00 AS yellow_amount, 
                    '' AS drawno,
                    '' AS bet_status 
                FROM loading a
                WHERE a.active = 'N' AND a.user_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- TYPE 2: Cash-Out (user is being debited)
                SELECT 
                    a.date_created, a.amount, 
                    2 AS type,
                    CONCAT(
                        (SELECT CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END FROM users WHERE id = a.agent_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.agent_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby,
                    0 AS winner, 
                    0.00 AS red_amount, 
                    0.00 AS blue_amount, 
                    0.00 AS yellow_amount, 
                    '' AS drawno,
                    '' AS bet_status 
                FROM withdrawals a
                WHERE a.active = 'N' AND a.user_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- TYPE 5: Cash-In (Downline) (user/agent is debited)
                SELECT 
                    a.date_created, a.amount,
                    5 AS type,
                    CONCAT(
                        (SELECT CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END FROM users WHERE id = a.user_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.user_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby,
                    0 AS winner, 
                    0.00 AS red_amount, 
                    0.00 AS blue_amount, 
                    0.00 AS yellow_amount, 
                    '' AS drawno,
                    '' AS bet_status 
                FROM loading a
                WHERE a.active = 'N' AND a.agent_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- TYPE 6: Cash-Out (Downline) (user/agent is credited)
                SELECT 
                    a.date_created, a.amount,
                    6 AS type,
                    CONCAT(
                        (SELECT CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END FROM users WHERE id = a.user_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.user_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby,
                    0 AS winner, 
                    0.00 AS red_amount, 
                    0.00 AS blue_amount, 
                    0.00 AS yellow_amount, 
                    '' AS drawno,
                    '' AS bet_status 
                FROM withdrawals a
                WHERE a.active = 'N' AND a.agent_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- TYPE 3: Commission (user is being credited)
                SELECT 
                    a.date_created, a.amount_converted AS amount, 
                    3 AS type,
                    CONCAT(
                        (SELECT CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END FROM users WHERE id = a.agent_id),
                        ' - ', 
                        (SELECT username FROM users WHERE id = a.agent_id)
                    ) AS accttyp,
                    (SELECT username FROM users WHERE id = a.agent_id) AS processby,
                    0 AS winner, 
                    0.00 AS red_amount, 
                    0.00 AS blue_amount, 
                    0.00 AS yellow_amount, 
                    '' AS drawno,
                    '' AS bet_status 
                FROM coms_converted a 
                WHERE a.user_id = ? AND a.date_created >= ?
            )
            UNION ALL
            ( -- TYPE 4: Winnings/Bets (user's net amount)
                SELECT 
                    a.date_created, 
                    (a.earnings - a.red_amount - a.blue_amount - a.yellow_amount) AS amount, 
                    4 AS type,
                    CONCAT(
                        (SELECT drawno FROM draws WHERE id = a.drawid),
                        '-', 
                        (SELECT name FROM events WHERE id = (SELECT eventid FROM draws WHERE id = a.drawid))
                    ) AS accttyp,
                    '' AS processby,
                    (SELECT winner FROM draws WHERE id = a.drawid) AS winner,
                    a.red_amount, a.blue_amount, a.yellow_amount,
                    (SELECT drawno FROM draws WHERE id = a.drawid) AS drawno,
                    (SELECT active FROM draws WHERE id = a.drawid) AS bet_status 
                FROM bets a 
                WHERE a.user_id = ? AND a.date_created >= ?
            )
            ORDER BY date_created ASC, type
        ";

        $stmt = $this->conn->prepare($sql);

        // Bind parameters (sds + 6 instances of ss for the user ID and date)
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
        
        // 3. Process results to calculate running balance and finalize columns
        $rows = [];
        $bal = (float)$ending_amount;
        $i = 1;

        while ($row = $result->fetch_assoc()) {
            $row['amount'] = (float)$row['amount']; // Ensure amount is float for arithmetic
            $row['display_amount'] = $row['amount']; // Initialize display amount

            switch ((int)$row['type']) {
                case 0: // Beginning Balance/Cut-off
                    // Balance initialized with $ending_amount, no need to add here unless we skip the initialization step.
                    // To follow the Agent logic:
                    if ($i === 1) $bal = $row['amount']; // Re-align balance if row 0 exists
                    $transaction_type_name = $type_name;
                    break;
                case 1: // Cash-In (Credit)
                case 3: // Commission (Credit)
                case 6: // Cash-Out (Downline) (Credit to Agent)
                    $bal += $row['amount'];
                    $transaction_type_name = ['Cash-In', 'Commission', 'Cash-Out (Downline)'][($row['type'] - 1) % 3]; // Simplified naming
                    break;
                case 2: // Cash-Out (Debit)
                case 5: // Cash-In (Downline) (Debit from Agent)
                    $bal -= $row['amount'];
                    // The client-side code will handle the display_amount negative sign for these types, 
                    // but we ensure the balance calculation is correct.
                    $transaction_type_name = ['Cash-Out', '', 'Cash-In (Downline)'][($row['type'] - 2) % 3]; // Simplified naming
                    break;
                case 4: // Winnings/Bets (Credit/Debit)
                    $bal += $row['amount'];
                    // The client-side loop prefixes with 'Fight #', so we only need the accttyp content here.
                    $transaction_type_name = $row['accttyp'];
                    break;
            }
            
            // Format for client-side display
            $row['display_amount'] = number_format($row['amount'], 2, '.', '');
            
            $row['row_num'] = $i++;
            $row['current_balance'] = $bal;
            $row['transaction_type_name'] = $transaction_type_name;
            $rows[] = $row;
        }

        return $rows;
    }

    // public function getCommLogsOfAgent($userId) {
    //     // Step 1: Get the oldest date_created from bets/coms
    //     $sql_oldest = "
    //         SELECT MIN(a.date_created) AS oldest_date
    //         FROM bets a
    //         INNER JOIN coms b
    //             ON b.drawid = a.drawid AND b.player_id = a.user_id
    //         WHERE b.user_id = ? AND b.active = 'Y'
    //     ";
    //     $stmt = $this->conn->prepare($sql_oldest);
    //     $stmt->bind_param("i", $userId);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $oldestDate = $result->fetch_assoc()['oldest_date'] ?? null;
    //     $stmt->close();

    //     if (!$oldestDate) return []; // No records, return empty array

    //     // Step 2: Fetch all commissions and withdrawals from coms_converted starting from $oldestDate
    //     $sql = "
    //         SELECT 
    //             date_created AS `DATE`,
    //             '' AS `FIGHT#`,
    //             '' AS `EVENT`,
    //             '' AS `USERNAME`,
    //             '' AS `AGENT`,
    //             0 AS `BET`,
    //             amount_converted AS `COMMISSION`,
    //             'Withdrawal' AS `%EARN`,
    //             'WITHDRAWAL' AS `TYPE`
    //         FROM coms_converted
    //         WHERE agent_id = ? AND date_created >= ?
    //         UNION ALL
    //         SELECT
    //             a.date_created AS `DATE`,
    //             (SELECT drawno FROM draws WHERE id = a.drawid) AS `FIGHT#`,
    //             (SELECT name FROM events WHERE id = (SELECT eventid FROM draws WHERE id = a.drawid)) AS `EVENT`,
    //             (SELECT username FROM users WHERE id = a.user_id) AS `USERNAME`,
    //             (SELECT username FROM users WHERE id = b.user_id) AS `AGENT`,
    //             a.blue_amount + a.red_amount + a.yellow_amount AS `BET`,
    //             b.amount AS `COMMISSION`,
    //             CONCAT(
    //                 b.com_rate,
    //                 '% ',
    //                 (SELECT
    //                     CASE
    //                         WHEN (SELECT parentid FROM users WHERE id = a.user_id) = b.user_id THEN 'Earn direct player'
    //                         WHEN type = 1 THEN 'Earn Operator'
    //                         WHEN type = 2 THEN 'Earn Sub-Operator'
    //                         WHEN type = 3 THEN 'Earn Master Agent'
    //                         WHEN type = 4 THEN 'Earn Gold Agent'
    //                         WHEN type = 5 THEN 'Earn Player'
    //                         ELSE ''
    //                     END
    //                 FROM users WHERE id = b.user_id)
    //             ) AS `%EARN`,
    //             'COMMISSION' AS `TYPE`
    //         FROM bets a
    //         INNER JOIN coms b
    //             ON b.drawid = a.drawid AND b.player_id = a.user_id
    //         WHERE b.user_id = ? AND b.active = 'Y'
    //         ORDER BY `DATE` ASC
    //     ";

    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("isi", $userId, $oldestDate, $userId);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     $rows = [];
    //     $runningBalance = 0.00;

    //     while ($row = $result->fetch_assoc()) {
    //         if ($row['TYPE'] === 'WITHDRAWAL') {
    //             $runningBalance -= $row['COMMISSION'];
    //             $row['COMMISSION'] = -1 * $row['COMMISSION'];
    //         } else { // COMMISSION
    //             $runningBalance += $row['COMMISSION'];
    //         }
    //         $row['RUNNING_BALANCE'] = $runningBalance;
    //         $row['DATE_formatted'] = date("M-d-Y H:i:s", strtotime($row['DATE']));
    //         $row['BET_formatted'] = number_format($row['BET'], 2);
    //         $row['COMMISSION_formatted'] = number_format($row['COMMISSION'], 2);

    //         $rows[] = $row;
    //     }

    //     // Step 3: Show most recent first
    //     return array_reverse($rows);
    // }



    public function getAllAgentsUnderAgent($userId,$type) {
        if ($type == 1){ //use admin priv
            $qry = $this->conn->query("SELECT * from `users` where type = 2  and active in ('Y','N','F','T')");
        }else{
            $qry = $this->conn->query("SELECT * from `users` where type = 2  and active in ('Y','N','F','T') and parentid = '{$userId}' order by username asc ");
        }
        
        return $qry->fetch_all(MYSQLI_ASSOC);
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
    public function getBetHistoryOfUserAjax($userId)
    {
        // 1. Fetch the ending balance (which acts as the starting point for history)
        // Note: This logic determines the date ($ending_asof) and amount ($ending_amount) 
        // to start the ledger from.
        $qryBeginingbalance = $this->conn->query("
            SELECT T1.ending_asof, T1.amount, T1.type 
            FROM (
                SELECT ending_asof, amount, 'Cut-off Balance' AS type FROM endingbalance WHERE user_id = '{$userId}'
                UNION 
                SELECT DATE_FORMAT(date_added, '%Y-%m-%d %H:%i') AS ending_asof, 0 AS amount, 'Beginning Balance' AS type FROM users WHERE id = '{$userId}'
                ORDER BY ending_asof DESC LIMIT 1
            ) T1
        ");

        if ($qryBeginingbalance->num_rows > 0) {
            $rowevent = $qryBeginingbalance->fetch_assoc();
            $ending_asof = $rowevent['ending_asof'];
            $ending_amount = $rowevent['amount'];
            $type_name = $rowevent['type']; // The name for the starting row
        } else {
            $ending_asof = '01/01/1900';
            $ending_amount = 0;
            $type_name = 'Beginning Balance';
        }
        
        // 2. Prepare the main history query with UNION ALL
        $sql = "
            -- TYPE 0: Beginning Balance / Cut-off
            SELECT 0 AS winner, 0.00 AS red_amount, 0.00 AS blue_amount, 0.00 AS yellow_amount, 
                   '{$ending_asof}' AS date_created, {$ending_amount} AS amount, 
                   0 AS type, '{$type_name}' AS type_name, '' AS accttyp, '' AS processby, '' AS drawno, 0 AS sort_col, '' AS bet_status
            
            UNION ALL
            
            -- TYPE 1: Cash-In (User)
            SELECT 0 AS winner, 0.00 AS red_amount, 0.00 AS blue_amount, 0.00 AS yellow_amount, 
                   date_created, amount, 1 AS type, 'Cash-In' AS type_name,
                   (SELECT CONCAT(CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END, ' - ', username) FROM users WHERE id = a.agent_id) AS accttyp,
                   (SELECT username FROM users WHERE id = a.agent_id) AS processby, '' AS drawno, UNIX_TIMESTAMP(date_created) AS sort_col, '' AS bet_status
            FROM loading a 
            WHERE active = 'N' AND user_id = ? AND date_created >= '{$ending_asof}'

            UNION ALL

            -- TYPE 2: Cash-Out (User)
            SELECT 0 AS winner, 0.00 AS red_amount, 0.00 AS blue_amount, 0.00 AS yellow_amount, 
                   date_created, amount, 2 AS type, 'Cash-Out' AS type_name,
                   (SELECT CONCAT(CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END, ' - ', username) FROM users WHERE id = a.agent_id) AS accttyp,
                   (SELECT username FROM users WHERE id = a.agent_id) AS processby, '' AS drawno, UNIX_TIMESTAMP(date_created) AS sort_col, '' AS bet_status
            FROM withdrawals a 
            WHERE active = 'N' AND user_id = ? AND date_created >= '{$ending_asof}'

            UNION ALL

            -- TYPE 5: Cash-In (Downline - Debit from agent's perspective)
            SELECT 0 AS winner, 0.00 AS red_amount, 0.00 AS blue_amount, 0.00 AS yellow_amount, 
                   date_created, amount, 5 AS type, 'Cash-In (Downline)' AS type_name,
                   (SELECT CONCAT(CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END, ' - ', username) FROM users WHERE id = a.user_id) AS accttyp,
                   (SELECT username FROM users WHERE id = a.agent_id) AS processby, '' AS drawno, UNIX_TIMESTAMP(date_created) AS sort_col, '' AS bet_status
            FROM loading a 
            WHERE active = 'N' AND agent_id = ? AND date_created >= '{$ending_asof}'

            UNION ALL

            -- TYPE 6: Cash-Out (Downline - Credit to agent's perspective)
            SELECT 0 AS winner, 0.00 AS red_amount, 0.00 AS blue_amount, 0.00 AS yellow_amount, 
                   date_created, amount, 6 AS type, 'Cash-Out (Downline)' AS type_name,
                   (SELECT CONCAT(CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END, ' - ', username) FROM users WHERE id = a.user_id) AS accttyp,
                   (SELECT username FROM users WHERE id = a.agent_id) AS processby, '' AS drawno, UNIX_TIMESTAMP(date_created) AS sort_col, '' AS bet_status
            FROM withdrawals a 
            WHERE active = 'N' AND agent_id = ? AND date_created >= '{$ending_asof}'

            UNION ALL

            -- TYPE 3: Commission
            SELECT 0 AS winner, 0.00 AS red_amount, 0.00 AS blue_amount, 0.00 AS yellow_amount, 
                   date_created, amount, 3 AS type, 'Commission' AS type_name,
                   (SELECT CONCAT(CASE WHEN role = 1 THEN 'Financer Account' WHEN role = 2 THEN 'Operator' WHEN role = 3 THEN 'Sub-Operator' WHEN role = 4 THEN 'Master Agent' WHEN role = 5 THEN 'Player' ELSE '' END, ' - ', username) FROM users WHERE id = a.agent_id) AS accttyp,
                   (SELECT username FROM users WHERE id = a.agent_id) AS processby, '' AS drawno, UNIX_TIMESTAMP(date_created) AS sort_col, '' AS bet_status
            FROM coms a 
            WHERE active = 'N' AND user_id = ? AND date_created >= '{$ending_asof}'

            UNION ALL

            -- TYPE 4: Winnings/Bets
            SELECT (SELECT winner FROM draws WHERE id = a.drawid) AS winner, 
                   red_amount, blue_amount, yellow_amount, date_created, 
                   (earnings - red_amount - blue_amount - yellow_amount) AS amount, 
                   4 AS type, 'Winnings/Bets' AS type_name,
                   CONCAT((SELECT CASE WHEN is_Redeclared = 1 THEN CONCAT('Redeclared ',DATE_FORMAT(datetime_Redeclared, '%Y-%m-%d %T'),'- ') ELSE '' END FROM draws WHERE id = a.drawid),
                          (SELECT drawno FROM draws WHERE id = a.drawid),'-', 
                          (SELECT name FROM events WHERE id = (SELECT eventid FROM draws WHERE id = a.drawid))) AS accttyp,
                   '' AS processby, 
                   (SELECT drawno FROM draws WHERE id = a.drawid) AS drawno, UNIX_TIMESTAMP(date_created) AS sort_col, (SELECT active FROM draws WHERE id = a.drawid) AS bet_status
            FROM bets a 
            WHERE user_id = ? AND date_created >= '{$ending_asof}'
            
            ORDER BY date_created ASC
        ";

        $stmt = $this->conn->prepare($sql);
        // Bind the 6 placeholders for $userId
        $stmt->bind_param("iiiiii", $userId, $userId, $userId, $userId, $userId, $userId); 
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        // 3. Return the data
        if (!empty($rows)) {
            return ['status' => 'success', 'content' => $rows];
        } else {
            // Return only the initial balance row if no other transactions exist
            return ['status' => 'success', 'content' => [
                [
                    'date_created' => $ending_asof,
                    'amount' => $ending_amount,
                    'type' => 0,
                    'type_name' => $type_name,
                    'accttyp' => '',
                    'processby' => '',
                    'red_amount' => 0.00,
                    'blue_amount' => 0.00,
                    'yellow_amount' => 0.00,
                    'winner' => 0,
                    'drawno' => '',
                ]
            ]];
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