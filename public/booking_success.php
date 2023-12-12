<?php

require_once __DIR__ . '/process_booking.php';




$bookingDetails = [
    "island" => "Main island",
    "hotel" => "Centralhotellet",
    "arrival_date" => $startDate,
    "departure_date" => $endDate,
    "total_cost" => "12",
    "stars" => "3",
    "features" => [
        [
            "name" => "sauna",
            "cost" => 2
        ]
    ],
    "addtional_info" => [
        "greeting" => "Thank you" . $name . "for choosing Centralhotellet",
        "imageUrl" => "https://upload.wikimedia.org/wikipedia/commons/e/e2/Hotel_Boscolo_Exedra_Nice.jpg"
    ]
];

// Convert the array to JSON format
$jsonText = json_encode($bookingDetails, JSON_PRETTY_PRINT);

// Output the JSON text
echo $jsonText;
echo $result;


echo $startDate;
