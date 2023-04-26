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
        $task_board = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    public function updateTaskSection($id, $name, $content, $sort_index = 0) {
        global $pdo;

        $isSucceed = false;

        try {
            $sql = "UPDATE task_section SET name=:name, content=:content WHERE id=:id;";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $name, 'content' => $content, 'id' => $id]);
            $isSucceed = true;
        } catch (Exception $e) {
            $isSucceed = false;
        }

        return $isSucceed;
    }


    public function deleteTaskSectionById($id) {
        global $pdo;

        $isSucceed = false;

        try {
            $sql = "DELETE FROM task_section WHERE id=:id";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $isSucceed = true;
        } catch (Exception $e) {
            $isSucceed = false;
        }

        return $isSucceed;
    }

    // Task
    function createTask($name, $data) {
        global $pdo;

        $isSucceed = false;

        // try {
            $sql = "INSERT INTO task (name, content, complete_date, isCompleted, task_board_id, task_section_id)
                    VALUES (:name, :content, :complete_date, :isCompleted, :task_board_id, :task_section_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $data['name'], 
                'content' => $data['description'],
                'complete_date' => $data['complete-date'],
                'isCompleted' => 0,
                'task_board_id' => $data['task-board-id'],
                'task_section_id' => $data['task-section-id']
            ]);
            $isSucceed = true;
        // } catch (Exception $e) {
        //     return $isSucceed;
        // }

        return $isSucceed;
    }

    public function getTasksByIds($board_id, $section_id) {
        global $pdo;

        $sql = "SELECT * FROM task WHERE task_board_id=:board_id AND task_section_id=:section_id ORDER BY sort_index ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['board_id' => $board_id, 'section_id' => $section_id]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $tasks;
    }

    public function moveTaskToSection($task_id, $board_id, $section_id) {
        global $pdo;

        $isSucceed = false;

        $sql = "UPDATE task SET task_board_id=:task_board_id, task_section_id=:task_section_id WHERE id=:id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['task_board_id' => $board_id, 'task_section_id' => $section_id, 'id' => $task_id]);

        return true;
    }

    public function updateTaskSortIndex($task_id, $sort_index) {
        global $pdo;

        $isSucceed = false;

        $sql = "UPDATE task SET sort_index=:sort_index WHERE id=:id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['sort_index' => $sort_index, 'id' => $task_id]);

        return true;
    }
}