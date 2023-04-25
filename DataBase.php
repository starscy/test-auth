<?php

$host = '127.0.0.1';
$db   = 'test_db';
$user = 'test_vadim';
$pass = '123456';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);