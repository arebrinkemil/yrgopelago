<?php

require __DIR__ . '/../database/connect.php';


function checkPrice($postData)
{
    $startDate = $postData['start_date'];
    $endDate = $postData['end_date'];
    $roomType = $postData['room_type'];

    $roomPrices = getCurrentPrices();
    $days = daysBookedCalc($startDate, $endDate);
    $totalCost = $days * $roomPrices[$roomType];


    return $totalCost;
}



function getCurrentPrices()
{
    try {

        global $db;

        $cheapPrice = null;
        $mediumPrice = null;
        $expensivePrice = null;

        $stmt = $db->prepare('SELECT price FROM Rooms WHERE room_type = :room_type');
        $stmt->execute(['room_type' => 'Budget']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $cheapPrice = $result['price'];
        }

        $stmt->execute(['room_type' => 'Standard']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $mediumPrice = $result['price'];
        }

        $stmt->execute(['room_type' => 'Luxury']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $expensivePrice = $result['price'];
        }

        $prices = array(
            "Budget" => $cheapPrice,
            "Standard" => $mediumPrice,
            "Luxury" => $expensivePrice
        );

        return $prices;
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
