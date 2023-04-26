<?php

Class TaskController {
    public static function all() {
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
    }

    public static function create() {
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
    }
}