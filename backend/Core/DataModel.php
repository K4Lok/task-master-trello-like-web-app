<?php

Class DataModel {
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
        $task_board = $stmt->fetchAll();

        return $task_board;
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

    public function isUserExist($email) {
        global $pdo;
        $sql = "SELECT * FROM user WHERE email=:email";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();

        if (!$result) return false;

        return true;
    }

    public function isPasswordMatch($email, $password) {
        global $pdo;
        $sql = "SELECT * FROM user WHERE email=:email";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        $hashed_password = $user['password'];

        if (!password_verify($password, $hashed_password)) {
            return false;
        }

        return true;
    }

    public function updateToken($email, $token) {
        global $pdo;
        $sql = "UPDATE user SET token=:token WHERE email=:email";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['token' => $token, 'email' => $email]);
    }

    public function checkToken($email, $token) {
        global $pdo;
        $sql = "SELECT * FROM user WHERE email=:email";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user['token'] !== $token) {
            return false;
        }

        return true;
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

    public function getTaskSection($board_id) {
        global $pdo;

        $sql = "SELECT * FROM task_section WHERE task_board_id=:board_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['board_id' => $board_id]);
        $task_board = $stmt->fetchAll();

        return $task_board;
    }

    public function createTaskSection($id, $data) {
        global $pdo;

        $isSucceed = false;

        try {
            $sql = "INSERT INTO task_section (name, content, sort_index, task_board_id)
                    VALUES (:name, :content, 0, :task_board_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $data['name'], 'content' => $data['description'], 'task_board_id' => $id]);
            $isSucceed = true;
        } catch (Exception $e) {
            return $isSucceed;
        }

        return $isSucceed;
    }
}