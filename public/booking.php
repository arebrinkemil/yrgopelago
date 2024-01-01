<?php

require __DIR__ . '/../app/database/connect.php';
require '../app/posts/bookRoom.php';
require '../app/posts/bookedDates.php';
require '../app/posts/payment.php';

session_start();

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$guestName = $data['guestName'] ?? null;
$startDate = $data['startDate'] ?? null;
$endDate = $data['endDate'] ?? null;
$roomType = $data['roomType'] ?? null;
$paymentKey = $data['paymentKey'] ?? null;
$activities = $data['activities'] ?? [];




if (!empty($guestName) && !empty($startDate) && !empty($endDate) && !empty($roomType) && !empty($paymentKey)) {
    // First, check room availability
    $availability = checkRoomAvailability($db, $startDate, $endDate, $roomType);
    if ($availability === true) {
        error_log($_SESSION['totalPrice']);
        $totalCost = $_SESSION['totalPrice'];

        $paymentResult = processPayment($paymentKey, $totalCost);

        if ($paymentResult['success']) {

            $bookingResult = bookRoomAndActivities($db, $guestName, $startDate, $endDate, $roomType, $activities);

            if ($bookingResult['success']) {

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Booking successful',
                    'bookingId' => $bookingResult['bookingId'],
                    'guestId' => $bookingResult['guestId'],
                    'totalCost' => $_SESSION['totalPrice']
                ]);
            } else {

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Booking failed: ' . $bookingResult['error']
                ]);
            }
        } else {

            echo json_encode([
                'status' => 'error',
                'message' => $paymentResult['error']
            ]);
        }
    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Room is not available for the selected dates.'
        ]);
    }
} else {

    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required information.',
        'data' => $data
    ]);
}
