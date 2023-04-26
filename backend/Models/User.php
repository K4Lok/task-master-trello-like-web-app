<?php

Class User {
    public function isUserExist($email) {
        global $pdo;
        $sql = "SELECT * FROM user WHERE email=:email";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();

        if (!$result) return false;

        return true;
    }

    public function getUserIdByEmail($email) {
        global $pdo;
        $sql = "SELECT * FROM user WHERE email=:email";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user['id'];
    }

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

    public function updateToken($email, $token) {
        global $pdo;
        $sql = "UPDATE user SET token=:token WHERE email=:email";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['token' => $token, 'email' => $email]);
    }
}