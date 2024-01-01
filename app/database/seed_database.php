<?php
// Database connection - replace with your actual database connection details
$db = new PDO('sqlite:' . __DIR__ . '/hoteldatabase.db');

// Sample data
$guestNames = ['Alice', 'Bob', 'Charlie', 'Diana', 'Eve', 'Frank'];
$roomTypes = [1, 2, 3]; // Room IDs
$featureNames = ['Wi-Fi', 'Breakfast', 'Parking', 'Gym Access', 'Pool Access'];
$featureCosts = [5, 10, 15, 20, 25];

// Insert features into Hotel_Features table
foreach ($featureNames as $index => $name) {
    $cost = $featureCosts[$index];
    $db->exec("INSERT INTO Hotel_Features (name, cost) VALUES ('$name', $cost)");
}

// Populate Guests and Bookings for January
for ($day = 1; $day <= 31; $day++) {
    $arrivalDate = "2023-01-$day";
    $departureDate = "2023-01-" . ($day + 1);

    foreach ($guestNames as $name) {
        // Insert guest
        $db->exec("INSERT INTO Guests (name) VALUES ('$name')");
        $guestId = $db->lastInsertId();

        // Random room assignment
        $roomId = $roomTypes[array_rand($roomTypes)];
        $roomPriceQuery = $db->query("SELECT price FROM Rooms WHERE room_id = $roomId");
        $roomPrice = $roomPriceQuery->fetchColumn();

        // Calculate total cost (assuming 1 night stay)
        $totalCost = $roomPrice; // Add more logic if needed

        // Insert booking
        $db->exec("INSERT INTO Bookings (guest_id, room_id, arrival_date, departure_date, total_cost)
                   VALUES ($guestId, $roomId, '$arrivalDate', '$departureDate', $totalCost)");
    }
}

echo "Database populated with test data for January!";
