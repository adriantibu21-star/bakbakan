<?php

class UserBalance {
    private $conn;
    private $currentUser;
    private $type;

    function __construct($conn, $currentUser, $type) {
        $this->conn = $conn;
        $this->currentUser = $currentUser;
        $this->type = $type;
    }

    public function getActiveAgentUnderCurrrentUserCount() {
        $qry = $this->conn->query("SELECT COUNT(*) as total from users where parentid={$this->currentUser} and Type in (2) and id <> {$this->currentUser} and lower(active) = 'y' ");
        $row = $qry->fetch_assoc();
        return number_format($row['total'], 0);
    }

    public function getTotalAgentBalanceUnderCurrentUser() {
        if ($this->type == 1) { //use admin priv
            $qry = $this->conn->query("SELECT SUM(amount) as total from users where Type in (2) and id <> {$this->currentUser}");
        } else {
            $qry = $this->conn->query("SELECT SUM(amount) as total from users where parentid={$this->currentUser} and Type in (2) and id <> {$this->currentUser}");
        }
        $row = $qry->fetch_assoc();
        return number_format($row['total'], 2);
    }

    public function getTotalPlayerBalanceUnderCurrentUser() {
        if ($this->type == 1) { //use admin priv
            $qry = $this->conn->query("SELECT SUM(amount) as total from users where Type in (3) and id <> {$this->currentUser}");
        } else {
            $qry = $this->conn->query("SELECT SUM(amount) as total from users where parentid={$this->currentUser} and Type in (3) and id <> {$this->currentUser}");
        }
        $row = $qry->fetch_assoc();
        return number_format($row['total'], 2);
    }

    public function getActivePlayerUnderCurrrentUserCount() {
        $qry = $this->conn->query("SELECT COUNT(*) as total from users where parentid={$this->currentUser} and Type in (3) and id <> {$this->currentUser} and lower(active) = 'y' ");
        $row = $qry->fetch_assoc();
        return number_format($row['total'], 0);
    }

    public function getTotalAgentCommissionUnderCurrentUser() {
        $qry = $this->conn->query("SELECT SUM(com_amount_bal) as total from users where parentid={$this->currentUser} and Type in (2) and id <> {$this->currentUser}");
        $row = $qry->fetch_assoc();
        return number_format($row['total'], 2);
    }
}