<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: True");

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

    session_start();
    $_SESSION['uemail'] = $email;

    $response['message'] = "Password correct! Routing to your task board...";
    $response['succedd'] = true;
    $response['PHPSESSID'] = session_id();

    $model->updateToken($email, session_id());

    // $response['PHPSESSID'] = $_SESSION['uemail'];
    echo json_encode($response);
    exit();
});

$route->get('/auth', function() {
    header('Content-Type: application/json; charset=utf-8');
    session_start();

    if (!isset($_GET['token'])) {
        echo json_encode(["message" => "Token is missing!", "succedd" => false]);
        exit();
    }

    $token = $_GET['token'];

    if ($token !== session_id()) {
        echo json_encode(["message" => "Token is incorrect!", "succedd" => false, "correct_token" => session_id(), "your_token" => $token]);
        exit();
    }

    echo json_encode(["message" => "Token is correct!", "succedd" => true]);
    exit();
});

$route->run();