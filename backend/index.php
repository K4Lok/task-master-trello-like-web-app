<?php

require 'config.php';
require 'database.php';
require __DIR__ . '/Models/User.php';
require __DIR__ . '/Models/DataModel.php';
require __DIR__ . '/Core/Authentication.php';
require 'routes.php';

// $sql = 'SELECT * FROM task_board';
// $statement = $pdo->query($sql);

// $publishers = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($publishers);