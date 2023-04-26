<?php

Class TaskBoardController {
    public static function all() {
        header('Content-Type: application/json; charset=utf-8');

        global $auth;
        $auth->get_auth();
    
        $uemail = $_GET['uemail'];
    
        $model = new DataModel();
        $task_board = $model->getTaskBoard($uemail);
    
        echo json_encode($task_board);
        exit();
    }

    public static function name() {
        if (!isset($_GET['id'])) {
            echo json_encode(["message" => "Params is missing!", "succeed" => false]);
            exit();
        }
    
        $id = $_GET['id'];
    
        $model = new DataModel();
        $taskBoardName = $model->getTaskBoardNameByTaskBoardId($id);
    
        echo json_encode(["name" => $taskBoardName, "succeed" => true]);
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

        $user = new UserModel();

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

        $model = new DataModel();

        $isSucceed = $model->updateTaskBoard($id, $name, $description);

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
        $model = new DataModel();

        $isSucceed = $model->deleteTaskBoardById($id);

        if (!$isSucceed) {
            echo json_encode(["message" => "Delete operationg encouter error, please try again!", "succeed" => false]);
            exit();
        }

        echo json_encode(["message" => "Delete operationg succeed!", "succeed" => true]);
        exit();
    }
}