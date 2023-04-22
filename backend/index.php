<?php

require 'config.php';
require 'database.php';

$sql = 'SELECT * FROM task_board';
$statement = $pdo->query($sql);

$publishers = $statement->fetchAll(PDO::FETCH_ASSOC);
print_r($publishers);