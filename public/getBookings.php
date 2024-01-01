<?php
require '../app/database/connect.php';

$roomId = $_GET['room_id'] ?? null;

if ($roomId === null) {

    exit;
}

try {
    $stmt = $db->prepare("SELECT arrival_date, departure_date FROM Bookings WHERE room_id = ?");
    $stmt->execute([$roomId]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $events = [];
    foreach ($bookings as $booking) {
        $events[] = [
            'start' => $booking['arrival_date'],
            'end' => $booking['departure_date'],
            'title' => 'Booked'
        ];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo json_encode($events);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
