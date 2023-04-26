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
}