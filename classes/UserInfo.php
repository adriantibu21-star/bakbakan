<?php

class UserInfo {
    private $conn;
    private $currentUser;
    private $type;

    function __construct($conn, $currentUser, $type) {
        $this->conn = $conn;
        $this->currentUser = $currentUser;
        $this->type = $type;
    }

    public function getAllPlayersUnderAgent() {
        if ($this->type == 1){ //use admin priv
            $qry = $this->conn->query("SELECT * from `users` where type = 3  and active in ('Y','N','F','T')");
        }else{
            $qry = $this->conn->query("SELECT * from `users` where type = 3  and active in ('Y','N','F','T') and parentid = '{$this->currentUser}' order by username asc ");
        }
        
        return $qry->fetch_all(MYSQLI_ASSOC);
    }
}