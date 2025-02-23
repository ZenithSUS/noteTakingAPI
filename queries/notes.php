<?php
include_once 'token.php';
class Notes extends Token {

    protected function __construct() {
        parent::__construct();
    }

    /**
     * Get Notes
     * @return array | string
     */
    protected function getNotes() : array | string {
        $sql = "SELECT id, title, content FROM notes ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);

        if(!$stmt) {
            return $this->queryFailed();
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $this->fetched($result) : $this->notFound();
    }

    /**
     * Add Note
     * @param string $title
     * @param string $content
     * @return string
     */
    protected function addNote(?string $title = null, ?string $content = null) : string {
        $sql = "INSERT INTO notes (id, title, content) VALUES (UUID(), ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if(!$stmt) {
            return $this->queryFailed();
        }

        $stmt->bind_param('ss', $title, $content);
        $stmt->execute();
        return $stmt->affected_rows > 0 ? $this->success('add') : $this->queryFailed();
    }
}
?>