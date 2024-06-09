<?php
include 'db_connection.php';
function getAllUsers()
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM user");

    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    header("Content-Type: application/json");
    if ($user) {
        echo json_encode(["success" => true, "user" => $user]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to fetch users"]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    getAllUsers();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
