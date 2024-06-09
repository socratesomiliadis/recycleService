<?php
include 'db_connection.php';

function makeRequest($rewardId, $userId)
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("INSERT INTO request (reward_id, user_id, pending) VALUES (:rewardId, :userId, 1)");
    $stmt->bindParam(':rewardId', $rewardId);
    $stmt->bindParam(':userId', $userId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Request made successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to make request"]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rewardId = $_POST['rewardId'];
    $userId = $_POST['userId'];
    makeRequest($rewardId, $userId);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
