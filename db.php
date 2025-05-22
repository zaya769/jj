<?php
$host = 'localhost';
$db = 'portfolio_db';     // <-- Өөрийн DB нэрээ оруулна
$user = 'root';                 // XAMPP хэрэглэж байгаа бол root
$pass = '';                     // XAMPP дээр нууц үг хоосон
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo "Холболт амжилтгүй боллоо: " . $e->getMessage();
    exit();
}
?>
