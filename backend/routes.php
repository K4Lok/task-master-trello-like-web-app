<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: True");

require __DIR__ . '/Core/Router.php';
require __DIR__ . '/Controllers/UserController.php';
require __DIR__ . '/Controllers/TaskBoardController.php';

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

$route->post('/api/task-board/delete', function() {
    TaskBoardController::delete();
});

$route->post('/api/task-board/update', function() {
    header('Content-Type: application/json; charset=utf-8');

    global $auth;
    $auth->post_auth();

    if (!isset($_POST['id']) || !isset($_POST['board-name']) || !isset($_POST['description'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

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

    global $auth;
    $auth->get_auth();

    if (!isset($_GET['id'])) {
        echo json_encode(["message" => "Params is missing!", "succeed" => false]);
        exit();
    }
    $id = $_GET['id'];

    $model = new DataModel();

    $task_board = $model->getTaskSection($id);

    echo json_encode($task_board);
    exit();
});

$route->post('/api/task-section/create', function() {
    header('Content-Type: application/json; charset=utf-8');

    global $auth;
    $auth->post_auth();

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

    global $auth;
    $auth->post_auth();

    if (!isset($_POST['id']) || !isset($_POST['section-name']) || !isset($_POST['description'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

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

    global $auth;
    $auth->post_auth();

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

$route->post('/api/task/create', function() {
    header('Content-Type: application/json; charset=utf-8');

    global $auth;
    $auth->post_auth();

    if (!isset($_POST['task-board-id']) || !isset($_POST['task-section-id']) || !isset($_POST['task-name']) || !isset($_POST['description']) || !isset($_POST['complete-date'])) {
        echo json_encode(["message" => "Data is missing!", "succeed" => false]);
        exit();
    }

    $taskBoardId = $_POST['task-board-id'];
    $taskSectionId = $_POST['task-section-id'];
    $name = $_POST['task-name'];
    $description = $_POST['description'];
    $completeDate = $_POST['complete-date'];

    $data = [
        'task-board-id' => $taskBoardId,
        'task-section-id' => $taskSectionId,
        "name" => $name, 
        "description" => $description,
        "complete-date" => $completeDate,
    ];

    $model = new DataModel();
    $isSucceed = $model->createTask($taskBoardId, $data);

    if (!$isSucceed) {
        echo json_encode(["message" => "We faced some issues on creating task board, please try again!", "succeed" => false]);
        exit();
    }

    echo json_encode(["message" => "Task Board created!", "succeed" => true]);
    exit();
});

$route->get('/api/task', function() {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_GET['board_id']) || !isset($_GET['section_id'])) {
        echo json_encode(["message" => "Params is missing!", "succeed" => false]);
        exit();
    }

    $board_id = $_GET['board_id'];
    $section_id = $_GET['section_id'];

    $model = new DataModel();
    $tasks = $model->getTasksByIds($board_id, $section_id);

    echo json_encode(["data" => $tasks, "succeed" => true]);
    exit();
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