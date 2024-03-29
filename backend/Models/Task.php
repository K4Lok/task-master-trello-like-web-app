<?php

Class Task {
    function createTask($name, $data) {
        global $pdo;

        $isSucceed = false;

        // try {
            $sql = "INSERT INTO task (name, content, complete_date, is_completed, task_board_id, task_section_id)
                    VALUES (:name, :content, :complete_date, :is_completed, :task_board_id, :task_section_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $data['name'], 
                'content' => $data['description'],
                'complete_date' => $data['complete-date'],
                'is_completed' => 0,
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

    public function getTaskNumberByBoardId($board_id) {
        global $pdo;

        $sql = "SELECT * FROM task WHERE task_board_id=:board_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['board_id' => $board_id]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return count($tasks);
    }

    public function updateTask($id, $name, $content, $complete_date) {
        global $pdo;

        $isSucceed = false;

        try {
            $sql = "UPDATE task SET name=:name, content=:content, complete_date=:complete_date WHERE id=:id;";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $name, 'content' => $content, 'complete_date' => $complete_date, 'id' => $id]);
            $isSucceed = true;
        } catch (Exception $e) {
            $isSucceed = false;
        }

        return $isSucceed;
    }

    public function deleteTaskById($id) {
        global $pdo;

        $isSucceed = false;

        try {
            $sql = "DELETE FROM task WHERE id=:id";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $isSucceed = true;
        } catch (Exception $e) {
            $isSucceed = false;
        }

        return $isSucceed;
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

    public function updateTaskComplete($id, $is_completed) {
        global $pdo;

        $isSucceed = false;

        $sql = "UPDATE task SET is_completed=:is_completed WHERE id=:id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['is_completed' => $is_completed, 'id' => $id]);

        return true;
    }
}