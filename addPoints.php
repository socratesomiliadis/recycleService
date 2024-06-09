<?php
include 'db_connection.php';

function addPoints($userId, $paper, $glass, $aluminum)
{
    $conn = getDbConnection();

    try {
        // Start transaction
        $conn->beginTransaction();

        // Update current points in user table
        $currentPointsStmt = $conn->prepare("UPDATE user SET currentPoints = currentPoints + :totalPoints WHERE id = :userId");
        $totalPoints = $paper + $glass + $aluminum;
        $currentPointsStmt->bindParam(':totalPoints', $totalPoints);
        $currentPointsStmt->bindParam(':userId', $userId);

        if (!$currentPointsStmt->execute()) {
            throw new Exception("Failed to update current points");
        }

        // Insert new record in points table
        $pointsStmt = $conn->prepare("UPDATE points SET paper = paper + :paper, glass = glass + :glass, aluminum = aluminum + :aluminum WHERE user_Id = :userId");
        $pointsStmt->bindParam(':userId', $userId);
        $pointsStmt->bindParam(':paper', $paper);
        $pointsStmt->bindParam(':glass', $glass);
        $pointsStmt->bindParam(':aluminum', $aluminum);

        if (!$pointsStmt->execute()) {
            throw new Exception("Failed to update points record");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Points added successfully"]);
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
    $paper = $_POST['paper'];
    $glass = $_POST['glass'];
    $aluminum = $_POST['aluminum'];
    addPoints($userId, $paper, $glass, $aluminum);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
