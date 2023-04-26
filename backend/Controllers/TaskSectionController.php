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
}