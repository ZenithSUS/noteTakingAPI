<?php
include_once '../headers.php';
include_once '../authorization_headers.php';
include_once '../queries/notes.php';

$requestMethod = $_SERVER["REQUEST_METHOD"] ?? null;
$process = isset($_POST['process']) ? $_POST['process'] : null;
$routeOptions = array("get-notes", "add-note");

$token = $headers['Authorization'] ?? null;
if(isset($token) && strpos($token, 'Bearer ') !== false) {
    $token = explode(' ', $token)[1];   
}

class NotesRequest extends Notes {

    public function __construct() {
        parent::__construct();
    }

    public function get() : string {
        return $this->getNotes();
    }

    public function verifyUserToken(?string $token) : bool {
        return $this->verifyToken($token);
    }

    public function unauthorizedData() : string {
        return $this->unauthorized();
    }

    public function bad() : string {
        return $this->badRequest();
    }
}


$notes = new NotesRequest();

if($requestMethod == 'OPTIONS') {
    http_response_code(200);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Authorization, X-Requested-With");
    exit();
}

if(!$notes->verifyUserToken($token)) {
    echo $notes->unauthorizedData();
    exit();
}


if($requestMethod == 'POST') {

    if($process && $process == 'get-notes') {
        echo $notes->get();
    }
}   

if(!in_array($process, $routeOptions)) {
    echo $notes->bad();
}
?>
