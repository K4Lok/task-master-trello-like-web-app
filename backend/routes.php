<?php

header("Access-Control-Allow-Origin: *");

require __DIR__ . '/Core/Router.php';

$route = new Router();

$route->get('/', function() {
    echo "home page";
});

$route->post('/signup', function() {
    header('Content-Type: application/json; charset=utf-8');
    $response = [];

    if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['email'])) {
        $response['message'] = "username, password, or email is missing!";
        $response['succedd'] = false;
        echo json_encode($response);
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $model = new Model();
    $isSucced = $model->createUser($username, $password, $email);

    if (!$isSucced) {
        $response['message'] = "Entered email existed!";
        $response['succedd'] = false;

        echo json_encode($response);
        exit();
    }

    $response['message'] = "Your account was created successfully!";
    $response['succedd'] = true;

    http_response_code(200);
    echo json_encode($response);

    exit();
});

$route->post('/login', function() {
    header('Content-Type: application/json; charset=utf-8');
    $response = [];

    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        $response['message'] = "email or password is missing!";
        $response['succedd'] = false;
        echo json_encode($response);
        exit();
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $model = new Model();
    
    if (!$model->isUserExist($email)) {
        $response['message'] = "User does not exists!";
        $response['succedd'] = false;
        echo json_encode($response);
        exit();
    }

    if (!$model->isPasswordMatch($email, $password)) {
        $response['message'] = "Password is not correct!";
        $response['succedd'] = false;
        echo json_encode($response);
        exit();
    }

    $response['message'] = "Password correct! Routing to your task board...";
    $response['succedd'] = true;
    echo json_encode($response);
    exit();

});

$route->run();