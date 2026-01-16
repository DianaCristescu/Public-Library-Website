<?php
    $host = '127.0.0.1';
    $db   = 'biblioteca_database';
    $user = 'root';
    $pass = '';

    // $host = 'localhost'; 
    // $db   = 'deeana325_unibuc_biblioteca';
    // $user = 'deeana325_admin';
    // $pass = 'c#u0v7^MYJqjjag8';

    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        die("Database Connection Failed: " . $e->getMessage());
    }

    // For Debugging Errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Start session (for user data) & Connect to the database
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>