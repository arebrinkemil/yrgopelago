<?php

require __DIR__ . '/../database/connect.php';

header('Content-Type: application/json');

$roomType = isset($_GET['room_type']) ? $_GET['room_type'] : null;

$sql = "SELECT Bookings.booking_id, Bookings.arrival_date, Bookings.departure_date FROM Bookings ";
$sql .= "JOIN Rooms ON Bookings.room_id = Rooms.room_id ";

if ($roomType) {
    $sql .= "WHERE Rooms.room_type = :roomType ";
}

$stmt = $db->prepare($sql);

if ($roomType) {
    $stmt->bindParam(':roomType', $roomType, PDO::PARAM_STR);
}

$stmt->execute();

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
