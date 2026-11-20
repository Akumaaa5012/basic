<?php

// Defaults match a local WAMP/XAMPP install. On a server, put real values in
// api/config.local.php (not committed) - see deploy/README.md.
$config = [];
if (is_file(__DIR__ . "/config.local.php")) {
    $config = require __DIR__ . "/config.local.php";
}

$host = $config["host"] ?? "localhost";
$username = $config["username"] ?? "root";
$password = $config["password"] ?? "";
$database = $config["database"] ?? "sproutroute";
$port = (int) ($config["port"] ?? 3306);

mysqli_report(MYSQLI_REPORT_OFF);

$conn = @new mysqli(
    $host,
    $username,
    $password,
    $database,
    $port
);

if ($conn->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode([
        "ok" => false,
        "error" => "Database connection failed."
    ]);
    exit;
}

$conn->set_charset("utf8mb4");
