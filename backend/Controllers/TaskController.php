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

        $task = new Task();
        $tasks = $task->getTasksByIds($board_id, $section_id);

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

        $task = new Task();
        $isSucceed = $task->createTask($taskBoardId, $data);

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

        if (!isset($_POST['id']) || !isset($_POST['task-name']) || !isset($_POST['description']) || !isset($_POST['complete-date'])) {
            echo json_encode(["message" => "Data is missing!", "succeed" => false]);
            exit();
        }

        $id = $_POST['id'];
        $name = $_POST['task-name'];
        $description = $_POST['description'];
        $completeDate = $_POST['complete-date'];

        $task = new Task();

        $isSucceed = $task->updateTask($id, $name, $description, $completeDate);

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
        $task = new Task();

        $isSucceed = $task->deleteTaskById($id);

        if (!$isSucceed) {
            echo json_encode(["message" => "Delete operationg encouter error, please try again!", "succeed" => false]);
            exit();
        }

        echo json_encode(["message" => "Delete operationg succeed!", "succeed" => true]);
        exit();
    }

    public static function move() {
        header('Content-Type: application/json; charset=utf-8');

        global $auth;
        $auth->post_auth();

        $task_id = $_POST['task_id'];
        $board_id = $_POST['board_id'];
        $section_id = $_POST['section_id'];

        $task = new Task();
        $isSucceed = $task->moveTaskToSection($task_id, $board_id, $section_id);

        echo json_encode(["message" => "Task has been moved to new section successfully.", "succeed" => true]);
        exit();
    }

    public static function sort() {
        header('Content-Type: application/json; charset=utf-8');

        global $auth;
        $auth->post_auth();
    
        if (!isset($_POST['task_id']) || !isset($_POST['sort_index'])) {
            echo json_encode(["message" => "Data is missing!", "succeed" => false]);
            exit();
        }
    
        $task_id = $_POST['task_id'];
        $sort_index = $_POST['sort_index'];
    
        $task = new Task();
        $isSucceed = $task->updateTaskSortIndex($task_id, $sort_index);
    
        echo json_encode(["message" => "Task sort index has been updated successfully.", "succeed" => true]);
        exit();
    }

    public static function complete() {
        header('Content-Type: application/json; charset=utf-8');

        global $auth;
        $auth->post_auth();
    
        if (!isset($_POST['task_id']) || !isset($_POST['complete_date'])) {
            echo json_encode(["message" => "Data is missing!", "succeed" => false]);
            exit();
        }
    
        $task_id = $_POST['task_id'];
        $complete_date = $_POST['complete_date'];
    
        $task = new Task();
        $isSucceed = $task->updateTaskComplete($task_id, $complete_date);
    
        echo json_encode(["message" => "Task complete has been changed successfully.", "succeed" => true]);
        exit();
    }
}