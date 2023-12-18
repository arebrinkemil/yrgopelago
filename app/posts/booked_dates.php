<?php

require __DIR__ . '/../database/connect.php';

header('Content-Type: application/json');

$sql = "SELECT booking_id, arrival_date, departure_date FROM Bookings";
$stmt = $db->query($sql);

$bookings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $endDate = new DateTime($row['departure_date']);
    $endDate->modify('+1 day');
    $bookings[] = [
        'title' => "Booking #" . $row['booking_id'],
        'start' => $row['arrival_date'],
        'end' => $endDate->format('Y-m-d'),
    ];
}

echo json_encode($bookings);
