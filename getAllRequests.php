<?php
include 'db_connection.php';
function getAllRequests()
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM request");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "requests" => $requests]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    getAllRequests();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
