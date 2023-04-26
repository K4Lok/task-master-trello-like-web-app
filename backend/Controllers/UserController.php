<?php

Class UserController {
    public static function signup() {
        header('Content-Type: application/json; charset=utf-8');
        $response = [];

        if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['email'])) {
            $response['message'] = "username, password, or email is missing!";
            $response['succeed'] = false;
            echo json_encode($response);
            exit();
        }

        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        $user = new UserModel();
        $isSucceed = $user->createUser($username, $password, $email);

        if (!$isSucceed) {
            $response['message'] = "Entered email existed!";
            $response['succeed'] = false;

            echo json_encode($response);
            exit();
        }

        $response['message'] = "Your account was created successfully!";
        $response['succeed'] = true;

        http_response_code(200);
        echo json_encode($response);

        exit();
    }

    public static function login() {
        
    }
}