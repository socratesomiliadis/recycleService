<?php
include 'db_connection.php';

function getTopRecyclers()
{
    $conn = getDbConnection();

    try {
        // Query to get the total historical points for each user, sorted in descending order
        $stmt = $conn->prepare("
            SELECT 
                u.username, 
                SUM(p.paper + p.glass + p.aluminum) AS totalPoints
            FROM 
                user u
            JOIN 
                points p ON u.id = p.user_id
            GROUP BY 
                u.username
            ORDER BY 
                totalPoints DESC
        ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(["success" => true, "topRecyclers" => $result]);
        } else {
            echo json_encode(["success" => false, "message" => "No recyclers found"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    getTopRecyclers();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
