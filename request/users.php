<?php
include_once '../headers.php';
include_once '../queries/users.php';

$requestMethod = $_SERVER["REQUEST_METHOD"] ?? null;
$process = isset($_POST['process']) ? $_POST['process'] : null;
$routeOptions = array("get-username");

if(function_exists('getallheaders')) {
    $headers = getallheaders();
    if(isset($headers['Authorization'])) {
        $headers['Authorization'] = $headers['Authorization'];
    } elseif(isset($headers['X-Authorization'])) {
        $headers['Authorization'] = $headers['X-Authorization'];
    }
} else if(function_exists('apache_request_headers')) {
    $headers = apache_request_headers();
} else {
    $headers = array();
    if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers['Authorization'] = $_SERVER['HTTP_AUTHORIZATION'];
    } else if(isset($_SERVER['HTTP_X_AUTHORIZATION'])) {
        $headers['Authorization'] = $_SERVER['HTTP_X_AUTHORIZATION'];
    } else {
        $headers['Authorization'] = null;
    }
}  

$token = $headers['Authorization'] ?? null;
if(isset($token) && strpos($token, 'Bearer ') !== false) {
    $token = explode(' ', $token)[1];
}


class UsersRequest extends Users {
    public function __construct() {
        parent::__construct();
    }

    public function getUserAccount(string $token) : string {
        return json_encode(["username" => $this->getUsername($token)]);
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

$users = new UsersRequest();

if($requestMethod == 'OPTIONS') {
    http_response_code(200);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Authorization, X-Requested-With");
    exit();
}

if(!$users->verifyUserToken($token)) {
    echo $users->unauthorizedData();
    exit();
}

if($requestMethod == 'POST') {

    if($process && $process == 'get-username') {
        echo $users->getUserAccount($token);
    }
    
    if(!$process || !in_array($process, $routeOptions)) {
        echo $users->bad();
    }
}

?>