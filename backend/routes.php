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
});

$route->post('/login', function() {
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

    // $response['PHPSESSID'] = $_SESSION['uemail'];
    echo json_encode($response);
    exit();
});

$route->get('/auth', function() {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_GET['token']) || !isset($_GET['uemail'])) {
        echo json_encode(["message" => "Params is missing!", "succeed" => false]);
        exit();
    }

    $token = $_GET['token'];
    $uemail = $_GET['uemail'];

    $user = new UserModel();
    $tokenMatched = $user->checkToken($uemail, $token);

    if (!$tokenMatched) {
        echo json_encode(["message" => "Token is not matched!", "succeed" => false]);
        exit();
    }

    echo json_encode(["message" => "Token is correct!", "succeed" => true]);
    exit();
});

$route->get('/api/task-board', function() {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_GET['token']) || !isset($_GET['uemail'])) {
        echo json_encode(["message" => "Params is missing!", "succeed" => false]);
        exit();
    }

    $token = $_GET['token'];
    $uemail = $_GET['uemail'];

    $user = new UserModel();
    $tokenMatched = $user->checkToken($uemail, $token);

    if (!$tokenMatched) {
        echo json_encode(["message" => "Token is not matched!", "succeed" => false]);
        exit();
    }

    $model = new DataModel();
    $task_board = $model->getTaskBoard($uemail);

    echo json_encode($task_board);
    exit();
});

$route->post('/api/task-board/create', function() {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_POST['token']) || !isset($_POST['uemail'])) {
        echo json_encode(["message" => "Token is missing!", "succeed" => false]);
        exit();
    }

    if (!isset($_POST['board-name']) || !isset($_POST['description'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

    $token = $_POST['token'];
    $uemail = $_POST['uemail'];

    $name = $_POST['board-name'];
    $description = $_POST['description'];

    $user = new UserModel();
    $tokenMatched = $user->checkToken($uemail, $token);

    if (!$tokenMatched) {
        echo json_encode(["message" => "Token is not matched!", "succeed" => false]);
        exit();
    }

    $userId = $user->getUserIdByEmail($uemail);

    $data = [
        "name" => $name, 
        "description" => $description,
    ];

    $model = new DataModel();
    $isSucceed = $model->createTaskBoard($userId, $data);

    if (!$isSucceed) {
        echo json_encode(["message" => "We faced some issues on creating task board, please try again!", "succeed" => false]);
        exit();
    }

    echo json_encode(["message" => "Task Board created!", "succeed" => true]);
    exit();
});

$route->post('/api/task-board/delete', function() {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_POST['token']) || !isset($_POST['uemail'])) {
        echo json_encode(["message" => "Token is missing!", "succeed" => false]);
        exit();
    }

    if (!isset($_POST['id'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

    $token = $_POST['token'];
    $uemail = $_POST['uemail'];

    $id = $_POST['id'];
    $model = new DataModel();

    $isSucceed = $model->deleteTaskBoardById($id);

    if (!$isSucceed) {
        echo json_encode(["message" => "Delete operationg encouter error, please try again!", "succeed" => false]);
        exit();
    }

    echo json_encode(["message" => "Delete operationg succeed!", "succeed" => true]);
    exit();
});

$route->post('/api/task-board/update', function() {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_POST['token']) || !isset($_POST['uemail'])) {
        echo json_encode(["message" => "Token is missing!", "succeed" => false]);
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['board-name']) || !isset($_POST['description'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

    $token = $_POST['token'];
    $uemail = $_POST['uemail'];

    $id = $_POST['id'];
    $name = $_POST['board-name'];
    $description = $_POST['description'];

    $model = new DataModel();

    $isSucceed = $model->updateTaskBoard($id, $name, $description);

    if (!$isSucceed) {
        echo json_encode(["message" => "Update operationg encouter error, please try again!", "succeed" => false]);
        exit();
    }

    echo json_encode(["message" => "Update operationg succeed!", "succeed" => true]);
    exit();
});

$route->get('/api/task-section', function() {
    header('Content-Type: application/json; charset=utf-8');

    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_GET['token']) || !isset($_GET['uemail']) || !isset($_GET['id'])) {
        echo json_encode(["message" => "Params is missing!", "succeed" => false]);
        exit();
    }

    $token = $_GET['token'];
    $uemail = $_GET['uemail'];
    $id = $_GET['id'];

    $user = new UserModel();
    $tokenMatched = $user->checkToken($uemail, $token);

    if (!$tokenMatched) {
        echo json_encode(["message" => "Token is not matched!", "succeed" => false]);
        exit();
    }

    $model = new DataModel();

    $task_board = $model->getTaskSection($id);

    echo json_encode($task_board);
    exit();
});

$route->post('/api/task-section/create', function() {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_POST['token']) || !isset($_POST['uemail'])) {
        echo json_encode(["message" => "Token is missing!", "succeed" => false]);
        exit();
    }

    if (!isset($_POST['task-board-id']) || !isset($_POST['section-name']) || !isset($_POST['description'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

    $token = $_POST['token'];
    $uemail = $_POST['uemail'];

    $user = new UserModel();
    $tokenMatched = $user->checkToken($uemail, $token);

    if (!$tokenMatched) {
        echo json_encode(["message" => "Token is not matched!", "succeed" => false]);
        exit();
    }

    $taskBoardId = $_POST['task-board-id'];
    $name = $_POST['section-name'];
    $description = $_POST['description'];

    $data = [
        "name" => $name, 
        "description" => $description,
    ];

    $model = new DataModel();
    $isSucceed = $model->createTaskSection($taskBoardId, $data);

    if (!$isSucceed) {
        echo json_encode(["message" => "We faced some issues on creating task board, please try again!", "succeed" => false]);
        exit();
    }

    echo json_encode(["message" => "Task Board created!", "succeed" => true]);
    exit();
});

$route->get('/api/task-board/id', function() {
    if (!isset($_GET['id'])) {
        echo json_encode(["message" => "Params is missing!", "succeed" => false]);
        exit();
    }

    $id = $_GET['id'];

    $model = new DataModel();
    $taskBoardName = $model->getTaskBoardNameByTaskBoardId($id);

    echo json_encode(["name" => $taskBoardName, "succeed" => true]);
    exit();
});

$route->post('/api/task-section/update', function() {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_POST['token']) || !isset($_POST['uemail'])) {
        echo json_encode(["message" => "Token is missing!", "succeed" => false]);
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['section-name']) || !isset($_POST['description'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

    $token = $_POST['token'];
    $uemail = $_POST['uemail'];

    $id = $_POST['id'];
    $name = $_POST['section-name'];
    $description = $_POST['description'];

    $model = new DataModel();

    $isSucceed = $model->updateTaskSection($id, $name, $description);

    if (!$isSucceed) {
        echo json_encode(["message" => "Update operationg encouter error, please try again!", "succeed" => false]);
        exit();
    }

    echo json_encode(["message" => "Update operationg succeed!", "succeed" => true]);
    exit();
});

$route->post('/api/task-section/delete', function() {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_POST['token']) || !isset($_POST['uemail'])) {
        echo json_encode(["message" => "Token is missing!", "succeed" => false]);
        exit();
    }

    if (!isset($_POST['id'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

    $token = $_POST['token'];
    $uemail = $_POST['uemail'];

    $id = $_POST['id'];
    $model = new DataModel();

    $isSucceed = $model->deleteTaskSectionById($id);

    if (!$isSucceed) {
        echo json_encode(["message" => "Delete operationg encouter error, please try again!", "succeed" => false]);
        exit();
    }

    echo json_encode(["message" => "Delete operationg succeed!", "succeed" => true]);
    exit();
});

$route->run();