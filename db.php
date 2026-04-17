<?php
$host = 'localhost';
$db   = 'healthcare_hospital';
$user = 'root';
$pass = ''; // XAMPP ব্যবহার করলে পাসওয়ার্ড খালি থাকে
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
     die("ডাটাবেস কানেকশন ফেল করেছে: " . $e->getMessage());
}
?>