<?php

Class TaskSectionController {
    public static function all() {
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
    }

    public static function create() {
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
    }

    public static function update() {
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
    }
}