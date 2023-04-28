<?php

Class TaskBoard {
    public function getTaskBoard($email) {
        global $pdo;
        $sql = "SELECT * FROM user WHERE email=:email";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        $id = $user['id'];

        $sql = "SELECT * FROM task_board WHERE user_id=:id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $task_board = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $task_board;
    }

    public function getTaskBoardNameByTaskBoardId($id) {
        global $pdo;

        $sql = "SELECT * FROM task_board WHERE id=:id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $task_board = $stmt->fetch();

        return $task_board['name'];
    }

    public function createTaskBoard($id, $data) {
        global $pdo;

        $isSucceed = false;

        // try {
            $sql = "INSERT INTO task_board (name, description, section_num, task_num, user_id)
                    VALUES (:name, :description, 0, 0, :user_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $data['name'], 'description' => $data['description'], 'user_id' => $id]);
            $isSucceed = true;
        // } catch (Exception $e) {
        //     return $isSucceed;
        // }

        return $isSucceed;
    }

    public function updateTaskBoard($id, $name, $description) {
        global $pdo;

        $isSucceed = false;

        try {
            $sql = "UPDATE task_board SET name=:name, description=:description WHERE id=:id;";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $name, 'description' => $description, 'id' => $id]);
            $isSucceed = true;
        } catch (Exception $e) {
            $isSucceed = false;
        }

        return $isSucceed;
    }

    public function updateSectionNumber($id, $section_num) {
        global $pdo;

        try {
            $sql = "UPDATE task_board SET section_num=:section_num WHERE id=:id;";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['section_num' => $section_num, 'id' => $id]);
        } catch (Exception $e) {
            // $isSucceed = false;
        }
    }

    public function updateTaskNumber($id, $task_num) {
        global $pdo;

        try {
            $sql = "UPDATE task_board SET task_num=:task_num WHERE id=:id;";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['task_num' => $task_num, 'id' => $id]);
        } catch (Exception $e) {
            // $isSucceed = false;
        }
    }

    public function deleteTaskBoardById($id) {
        global $pdo;

        $isSucceed = false;

        try {
            $sql = "DELETE FROM task_board WHERE id=:id";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $isSucceed = true;
        } catch (Exception $e) {
            $isSucceed = false;
        }

        return $isSucceed;
    }
}