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

// Testing
$route->get('/', function() {
    echo "home page";
});

// User Routes
$route->post('/signup', fn() => UserController::signup());
$route->post('/login', fn() => UserController::login());
$route->post('/auth', fn() => UserController::auth());

// Task Board Routes
$route->get('/api/task-board', fn() => TaskBoardController::all());
$route->post('/api/task-board/create', fn() => TaskBoardController::create());
$route->post('/api/task-board/update', fn() => TaskBoardController::update());
$route->post('/api/task-board/delete', fn() => TaskBoardController::delete());
$route->get('/api/task-board/id', fn() => TaskBoardController::name());

// Task Section Routes
$route->get('/api/task-section', fn() => TaskSectionController::all());
$route->post('/api/task-section/create', fn() => TaskSectionController::create());
$route->post('/api/task-section/update', fn() => TaskSectionController::update());
$route->post('/api/task-section/delete', fn() => TaskSectionController::delete());

// Task Routes
$route->get('/api/task', fn() => TaskController::all());
$route->post('/api/task/create', fn() => TaskController::create());
$route->post('/api/task/move', fn() => TaskController::move());
$route->post('/api/task/sort', fn() => TaskController::sort());

$route->run();