<?php

require '../app/posts/booking_handler.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_POST);
    $result = handleBooking($_POST);

    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
} else {
    echo "error 1";
}
?>

<!-- if ($_SERVER["REQUEST_METHOD"] == "POST") {
header('Content-Type: application/json');

$startDate = $_POST['start_date'];
$endDate = $_POST['end_date'];
$roomType = $_POST['room_type'];

$duration = (strtotime($endDate) - strtotime($startDate)) / 86400;
$costPerDay = ($roomType === 'expensive') ? 50 : (($roomType === 'medium') ? 30 : 15);
$totalCost = $duration * $costPerDay;

$bookingReceipt = [
"island" => "Main island",
"hotel" => "Centralhotellet",
"arrival_date" => $startDate,
"departure_date" => $endDate,
"total_cost" => strval($totalCost),
"stars" => "3",
"features" => [
["name" => "sauna", "cost" => 2]
],
"additional_info" => [
"greeting" => "Thank you for choosing Centralhotellet",
"imageUrl" => "https://upload.wikimedia.org/wikipedia/commons/e/e2/Hotel_Boscolo_Exedra_Nice.jpg"
]
];

echo json_encode($bookingReceipt, JSON_PRETTY_PRINT);
exit;
} -->