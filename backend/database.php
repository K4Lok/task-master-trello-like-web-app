<?php

$dsn = "mysql:host=$HOST;dbname=$DATABASE;charset=UTF8";

try {
    $pdo = new PDO($dsn, $USERNAME, $PASSWORD);

    // if ($pdo) echo "Connected to the $DATABASE database successfully!";
}
catch (PDOException $e) {
    echo $e->getMessage();
}