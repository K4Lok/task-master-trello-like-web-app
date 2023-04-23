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
        http_response_code(400);
        $response['message'] = "username, password, or email is missing!";
        echo json_encode($response);
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $model = new Model();
    $isSucced = $model->createUser($username, $password, $email);

    if (!$isSucced) {
        $response['message'] = "email existed!";
        $response['succedd'] = false;

        echo json_encode($response);
        exit();
    }

    $response['message'] = "account created successfully!";
    $response['succedd'] = true;

    http_response_code(200);
    echo json_encode($response);

    exit();
});

$route->run();