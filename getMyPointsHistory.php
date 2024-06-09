<?php
include 'db_connection.php';

function getMyPointsHistory($userId)
{
    $conn = getDbConnection();

    try {
        // Query to get points history for the user
        $stmt = $conn->prepare("SELECT * FROM points WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(["success" => true, "pointsHistory" => $result]);
        } else {
            echo json_encode(["success" => false, "message" => "No points history found for user"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    getMyPointsHistory($userId);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
