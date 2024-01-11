<?php

declare(strict_types=1); // Add strict types declaration

require '../app/database/connect.php';

header('Content-Type: application/json');

try {
    $activities = getHotelFeatures($db);

    echo json_encode([
        'status' => 'success',
        'activities' => $activities
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

function getHotelFeatures(PDO $db): array // Add type declaration for $db parameter and return type declaration
{
    $query = "SELECT name, description, cost, image_url FROM Hotel_Features";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
