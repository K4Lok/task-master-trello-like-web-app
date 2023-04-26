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
}