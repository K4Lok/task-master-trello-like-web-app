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
        header('Content-Type: application/json; charset=utf-8');
        $response = [];

        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            $response['message'] = "email or password is missing!";
            $response['succeed'] = false;
            echo json_encode($response);
            exit();
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = new UserModel();
        
        if (!$user->isUserExist($email)) {
            $response['message'] = "User does not exists!";
            $response['succeed'] = false;
            echo json_encode($response);
            exit();
        }

        if (!$user->isPasswordMatch($email, $password)) {
            $response['message'] = "Password is not correct!";
            $response['succeed'] = false;
            echo json_encode($response);
            exit();
        }

        session_start();
        $_SESSION['uemail'] = $email;

        $response['message'] = "Password correct! Routing to your task board...";
        $response['succeed'] = true;
        $response['PHPSESSID'] = session_id();

        $user->updateToken($email, session_id());

        echo json_encode($response);
        exit();
    }
}