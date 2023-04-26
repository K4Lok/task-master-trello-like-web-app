<?php

Class Authentication {
    public function post_auth() {
        if (!isset($_POST['token']) || !isset($_POST['uemail'])) {
            echo json_encode(["message" => "Token is missing!", "succeed" => false]);
            exit();
        }
    
        $token = $_POST['token'];
        $uemail = $_POST['uemail'];
    
        $user = new User();
        $tokenMatched = $user->checkToken($uemail, $token);
    
        if (!$tokenMatched) {
            echo json_encode(["message" => "Token is not matched!", "succeed" => false]);
            exit();
        }
    }

    public function get_auth() {
        if (!isset($_GET['token']) || !isset($_GET['uemail'])) {
            echo json_encode(["message" => "Params is missing!", "succeed" => false]);
            exit();
        }
    
        $token = $_GET['token'];
        $uemail = $_GET['uemail'];
    
        $user = new User();
        $tokenMatched = $user->checkToken($uemail, $token);
    
        if (!$tokenMatched) {
            echo json_encode(["message" => "Token is not matched!", "succeed" => false]);
            exit();
        }
    }
}