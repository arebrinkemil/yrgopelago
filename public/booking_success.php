<?php

require_once __DIR__ . '/process_booking.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    writeToDatabase($_SESSION['post']);
    header('Location: booking_success.php');
    exit;
}

$bookingDetails = [
    "island" => "Main island",
    "hotel" => "Centralhotellet",
    "arrival_date" => $_SESSION['post']['start_date'],
    "departure_date" => $_SESSION['post']['end_date'],
    "total_cost" => "12",
    "stars" => "3",
    "features" => [
        [
            "name" => "sauna",
            "cost" => 2
        ]
    ],
    "addtional_info" => [
        "greeting" => "Thank you " . $_SESSION['post']['name'] . " for choosing Centralhotellet",
        "imageUrl" => "https://upload.wikimedia.org/wikipedia/commons/e/e2/Hotel_Boscolo_Exedra_Nice.jpg"
    ]
];

// Convert the array to JSON format
$jsonText = json_encode($bookingDetails, JSON_PRETTY_PRINT);

// Output the JSON text
echo $jsonText;
