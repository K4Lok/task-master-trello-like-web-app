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

$route->post('/auth', function() {
    UserController::auth();
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
    TaskController::move();
});

$route->post('/api/task/sort', function() {
    TaskController::sort();
});

$route->run();