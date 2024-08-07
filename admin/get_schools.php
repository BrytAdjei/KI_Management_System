<?php
// Database connection
$host = 'localhost';
$db = 'ki_db';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

$query = $pdo->query("SELECT id, school_name FROM schools");
$schools = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($schools);
?>
