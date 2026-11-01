
<?php

header("Content-Type: application/json; charset=utf-8");

// Local React development origin
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    echo json_encode([
        "message" => "Use POST for registration."
    ]);

    exit;
}

require __DIR__ . "/config.php";

$input = json_decode(
    file_get_contents("php://input"),
    true
);

$email = trim($input["email"] ?? "");
$password = $input["password"] ?? "";

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);

    echo json_encode([
        "message" => "Please provide a valid email."
    ]);

    exit;
}

// Validate password
if (!is_string($password) || strlen($password) < 8) {
    http_response_code(422);

    echo json_encode([
        "message" => "Password must be at least 8 characters."
    ]);

    exit;
}

if (strlen($password) > 1024) {
    http_response_code(422);

    echo json_encode([
        "message" => "Password is too long."
    ]);

    exit;
}

try {
    // Check duplicate email
    $check = $pdo->prepare(
        "SELECT id FROM users WHERE email = :email LIMIT 1"
    );

    $check->execute([
        "email" => $email
    ]);

    if ($check->fetch()) {
        http_response_code(409);

        echo json_encode([
            "message" => "This email is already registered."
        ]);

        exit;
    }

    // Hash password
    $hash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    // Insert user
    $insert = $pdo->prepare(
        "INSERT INTO users (email, password_hash)
         VALUES (:email, :password_hash)"
    );

    $insert->execute([
        "email" => $email,
        "password_hash" => $hash
    ]);

    http_response_code(201);

    echo json_encode([
        "message" => "Account created successfully."
    ]);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "message" => "Could not create account. Please try again."
    ]);
}
