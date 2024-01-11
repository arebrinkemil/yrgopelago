<?php

declare(strict_types=1);

require '../app/database/connect.php';
require '../app/autoload.php';

header('Content-Type: application/json');

try {
    $roomPrices = getRoomPrices($db);
    $hotelFeatures = getHotelFeatures($db);

    echo json_encode([
        'status' => 'success',
        'roomPrices' => $roomPrices,
        'hotelFeatures' => $hotelFeatures
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

function getRoomPrices($db): array
{
    $query = "SELECT room_type, price FROM Rooms";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHotelFeatures($db): array
{
    $query = "SELECT name, cost FROM Hotel_Features";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
