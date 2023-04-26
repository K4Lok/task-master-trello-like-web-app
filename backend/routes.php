<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: True");

require __DIR__ . '/Core/Router.php';
require __DIR__ . '/Controllers/UserController.php';
require __DIR__ . '/Controllers/TaskBoardController.php';
require __DIR__ . '/Controllers/TaskSectionController.php';
require __DIR__ . '/Controllers/TaskController.php';

$route = new Router();
$auth = new Authentication();


$route->get('/', function() {
    echo "home page";
});

$route->post('/signup', function() { 
    UserController::signup();
});

$route->post('/login', function() {
    UserController::login();
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
    TaskBoardController::all();
});

$route->post('/api/task-board/create', function() {
    TaskBoardController::create();
});

$route->post('/api/task-board/update', function() {
    TaskBoardController::update();
});

$route->post('/api/task-board/delete', function() {
    TaskBoardController::delete();
});

$route->get('/api/task-board/id', function() {
    TaskBoardController::name();
});

$route->get('/api/task-section', function() {
    TaskSectionController::all();
});

$route->post('/api/task-section/create', function() {
    TaskSectionController::create();
});

$route->post('/api/task-section/update', function() {
    TaskSectionController::update();
});

$route->post('/api/task-section/delete', function() {
    TaskSectionController::delete();
});

$route->get('/api/task', function() {
    TaskController::all();
});

$route->post('/api/task/create', function() {
    TaskController::create();
});

$route->post('/api/task/move', function() {
    header('Content-Type: application/json; charset=utf-8');

    global $auth;
    $auth->post_auth();

    $task_id = $_POST['task_id'];
    $board_id = $_POST['board_id'];
    $section_id = $_POST['section_id'];

    $model = new DataModel();
    $isSucceed = $model->moveTaskToSection($task_id, $board_id, $section_id);

    echo json_encode(["message" => "Task has been moved to new section successfully.", "succeed" => true]);
    exit();
});

$route->post('/api/task/sort', function() {
    header('Content-Type: application/json; charset=utf-8');

    global $auth;
    $auth->post_auth();

    if (!isset($_POST['task_id']) || !isset($_POST['sort_index'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

    $task_id = $_POST['task_id'];
    $sort_index = $_POST['sort_index'];

    $model = new DataModel();
    $isSucceed = $model->updateTaskSortIndex($task_id, $sort_index);

    echo json_encode(["message" => "Task sort index has been updated successfully.", "succeed" => true]);
    exit();
});

$route->run();