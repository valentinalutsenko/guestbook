<?php
$host = 'localhost';
$db = 'book';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host; dbname=$db; user=$user; pass=$pass";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO:: ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo = new PDO($dsn, $user, $pass, $options); 

