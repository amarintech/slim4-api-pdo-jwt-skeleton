<?php

$container->set('db', function (){
    $db = [
            'driver' => 'mysql',
            'host' => 'your_host',
            'database' => 'your_database',
            'username' => 'login',
            'password' => 'pwd',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ];
    try {
        $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['database'], $db['username'], $db['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception('*Could not connect to database*');
    }
});
?>