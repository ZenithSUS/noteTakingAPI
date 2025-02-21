<?php
include_once 'token.php';

class Users extends Token {

    protected function __construct() {
        parent::__construct();
    }

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
}
?>