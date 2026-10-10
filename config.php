<?php
session_start();

// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'app_esup';
$username = 'root';
$password = 'root';
$charset = 'utf8mb4';
$port = 8889;

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}

function isLoggedIn()
{
    return !empty($_SESSION['user_id']);
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}
