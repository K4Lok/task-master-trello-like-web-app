<?php

Class TaskBoardController {
    public static function all() {
        header('Content-Type: application/json; charset=utf-8');

        global $auth;
        $auth->get_auth();
    
        $uemail = $_GET['uemail'];
    
        $task_board = new TaskBoard();
        $task_boards = $task_board->getTaskBoard($uemail);
    
        echo json_encode($task_boards);
        exit();
    }

    public static function name() {
        if (!isset($_GET['id'])) {
            echo json_encode(["message" => "Params is missing!", "succeed" => false]);
            exit();
        }
    
        $id = $_GET['id'];
    
        $task_board = new TaskBoard();
        $task_board_name = $task_board->getTaskBoardNameByTaskBoardId($id);
    
        echo json_encode(["name" => $task_board_name, "succeed" => true]);
        exit();
    }

    public static function create() {
        header('Content-Type: application/json; charset=utf-8');

        global $auth;
        $auth->post_auth();

        if (!isset($_POST['board-name']) || !isset($_POST['description'])) {
            echo json_encode(["message" => "Data is missing!", "succeed" => false]);
            exit();
        }

        $uemail = $_POST['uemail'];
        $name = $_POST['board-name'];
        $description = $_POST['description'];

        $user = new User();

        $userId = $user->getUserIdByEmail($uemail);

        $data = [
            "name" => $name, 
            "description" => $description,
        ];

        $task_board = new TaskBoard();
        $isSucceed = $task_board->createTaskBoard($userId, $data);

        if (!$isSucceed) {
            echo json_encode(["message" => "We faced some issues on creating task board, please try again!", "succeed" => false]);
            exit();
        }

        echo json_encode(["message" => "Task Board created!", "succeed" => true]);
        exit();
    }

    public static function update() {
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

        $task_board = new TaskBoard();

        $isSucceed = $task_board->updateTaskBoard($id, $name, $description);

        if (!$isSucceed) {
            echo json_encode(["message" => "Update operationg encouter error, please try again!", "succeed" => false]);
            exit();
        }

        echo json_encode(["message" => "Update operationg succeed!", "succeed" => true]);
        exit();
    }

    public static function delete() {
        header('Content-Type: application/json; charset=utf-8');

        global $auth;
        $auth->post_auth();

        if (!isset($_POST['id'])) {
            echo json_encode(["message" => "Data is missing!", "succeed" => false]);
            exit();
        }
        
        $id = $_POST['id'];
        $task_board = new TaskBoard();

        $isSucceed = $task_board->deleteTaskBoardById($id);

        if (!$isSucceed) {
            echo json_encode(["message" => "Delete operationg encouter error, please try again!", "succeed" => false]);
            exit();
        }

        echo json_encode(["message" => "Delete operationg succeed!", "succeed" => true]);
        exit();
    }
}