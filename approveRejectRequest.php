<?php
include 'db_connection.php';
function approveRejectRequest($requestId, $approve)
{
    // Convert the boolean value to an integer (0 or 1)
    $approveInt = $approve ? 1 : 0;

    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE request SET approved = :approve, pending = 0 WHERE id = :requestId");
    $stmt->bindParam(':approve', $approveInt, PDO::PARAM_INT); // Use PDO::PARAM_INT for integers
    $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT); // Ensure requestId is also treated as an integer

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Request status updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update request status"]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = isset($_POST['requestId']) ? (int)$_POST['requestId'] : null; // Convert to integer
    $approve = isset($_POST['approve']) ? filter_var($_POST['approve'], FILTER_VALIDATE_BOOLEAN) : false; // Validate boolean

    if ($requestId !== null) {
        approveRejectRequest($requestId, $approve);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request ID"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}