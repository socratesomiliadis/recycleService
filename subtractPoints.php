<?php
include 'db_connection.php';

function subtractPoints($userId, $pointsToSubtract)
{
    $conn = getDbConnection();

    try {
        // Start transaction
        $conn->beginTransaction();

        // Check if the user has enough points
        $currentPointsStmt = $conn->prepare("SELECT currentPoints FROM user WHERE id = :userId");
        $currentPointsStmt->bindParam(':userId', $userId);
        $currentPointsStmt->execute();
        $currentPoints = $currentPointsStmt->fetchColumn();

        if ($currentPoints < $pointsToSubtract) {
            throw new Exception("Insufficient points");
        }

        // Update current points in user table
        $updatePointsStmt = $conn->prepare("UPDATE user SET currentPoints = currentPoints - :pointsToSubtract WHERE id = :userId");
        $updatePointsStmt->bindParam(':pointsToSubtract', $pointsToSubtract);
        $updatePointsStmt->bindParam(':userId', $userId);

        if (!$updatePointsStmt->execute()) {
            throw new Exception("Failed to update current points");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Points subtracted successfully"]);
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            // Rollback transaction if in transaction
            $conn->rollBack();
        }
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $pointsToSubtract = $_POST['pointsToSubtract'];
    subtractPoints($userId, $pointsToSubtract);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
