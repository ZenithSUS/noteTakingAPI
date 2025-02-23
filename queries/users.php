<?php
include_once 'token.php';

class Users extends Token {

    protected function __construct() {
        parent::__construct();
    }

    /**
     * Get Users
     * @return array | string
     */
    protected function getUsers() : array | string {
        $sql = "SELECT username, email FROM users ORDER BY username ASC";
        $stmt = $this->conn->prepare($sql);

        if(!$stmt) {
            $this->queryFailed();
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $this->fetched($result) : $this->notFound();
    }

    /**
     * Get username by token 
     * @param string $token 
     * @return string
    */     
    protected function getUsername(string $token) : string {
        $sql = "SELECT username FROM users WHERE token = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['username'];
    }
}
?>