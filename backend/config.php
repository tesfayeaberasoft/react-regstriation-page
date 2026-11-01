
<?php

$host = "localhost";
$dbname = "registration_db";
$username = "root";
$dbpassword = "";

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    $pdo = new PDO(
        $dsn,
        $username,
        $dbpassword,
        $options
    );
} catch (PDOException $e) {
    http_response_code(500);

    header("Content-Type: application/json; charset=utf-8");

    echo json_encode([
        "message" => "Database connection failed."
    ]);

    exit;
}
