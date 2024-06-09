<?php
include 'db_connection.php';

function register($username, $password, $role) {
    $conn = getDbConnection();

    try {
        // Check if the username already exists
        $checkUsernameStmt = $conn->prepare("SELECT id FROM user WHERE username = :username");
        $checkUsernameStmt->bindParam(':username', $username);
        $checkUsernameStmt->execute();
        if ($checkUsernameStmt->fetch()) {
            echo json_encode(["success" => false, "message" => "Username already exists"]);
            return;
        }

        // Start transaction
        $conn->beginTransaction();

        // Insert user
        $stmt = $conn->prepare("INSERT INTO user (username, password, role) VALUES (:username, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert user");
        }

        $userId = $conn->lastInsertId();

        // Insert points for the user
        $pointsStmt = $conn->prepare("INSERT INTO points (user_id, paper, glass, aluminum) VALUES (:user_id, 0, 0, 0)");
        $pointsStmt->bindParam(':user_id', $userId);

        if (!$pointsStmt->execute()) {
            throw new Exception("Failed to insert points");
        }

        // Commit transaction
        $conn->commit();

        // Fetch the user information to return
        $userStmt = $conn->prepare("SELECT id, username, role FROM user WHERE id = :userId");
        $userStmt->bindParam(':userId', $userId);
        $userStmt->execute();
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "message" => "User registered successfully", "user" => $user]);

    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            // Rollback transaction if in transaction
            $conn->rollBack();
        }
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    register($username, $password, $role);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
