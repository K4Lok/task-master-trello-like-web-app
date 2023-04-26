<?php

Class TaskSection {
    public function getTaskSection($board_id) {
        global $pdo;

        $sql = "SELECT * FROM task_section WHERE task_board_id=:board_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['board_id' => $board_id]);
        $task_sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $task_sections;
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
}