<?php
include 'db_connection.php';
function getMyRequests($userId)
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM request WHERE user_id = :userId");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "requests" => $requests]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    getMyRequests($userId);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
