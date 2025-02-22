<?php
include_once('../headers.php');
include_once('../queries/auth.php');

$requestMethod = $_SERVER["REQUEST_METHOD"] ?? null;
$process = isset($_POST['process']) ? $_POST['process'] : null;
$routeOptions = array("login", "register", "logout");

class AuthRequest extends Auth {

    public function __construct(){
        parent::__construct();
    }
   
    public function login(string $account, string $password) : string {
        return $this->loginUser($account, $password);
    }

    public function register(string $email, string $username, string $password, string $confirmpassword) : string {
        return $this->registerUser($email, $username, $password, $confirmpassword);
    }

    public function logout(string $token) : string {
        return $this->logoutUser($token);
    }

    public function bad() : string {
        return $this->badRequest();
    }
}

$auth = new AuthRequest();

if($requestMethod == "POST") {

    if($process && $process == "login") {
        $account = $_POST['account'] ?? null;
        $password = $_POST['password'] ?? null;
        echo $auth->login($account, $password);
    }

    if($process && $process == "register") {
        $email = $_POST['email'] ?? null;
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirmpassword = $_POST['confirmpassword'] ?? null;
        echo $auth->register($email, $username, $password, $confirmpassword);
    }

    if($process && $process == "logout") {
        $token = $_POST['token'] ?? null;
        echo $auth->logout($token);
    }

    if(!in_array($process, $routeOptions)) {
        echo $auth->bad();
    }
}

if($requestMethod == "GET") {
    echo $auth->bad();
}


?>