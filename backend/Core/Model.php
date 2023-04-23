<?php

Class Model {
    public function createUser($username, $password, $email) {
        global $pdo;
        
        $userExisted = $this->isUserExist($email);

        $isSucceed = false;

        if ($userExisted) {
            $isSucceed = false;
            return $isSucceed;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO user (name, password, email) VALUES (:name, :password, :email)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $username, 'password' => $password, 'email' => $email]);
            $isSucceed = true;
        } catch (Exception $e) {
            return $isSucceed;
        }

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
}