

<?php


require __DIR__ . '/../database/connect.php';





function handleBooking($postData)
{
    //$firstName = $postData['first_name'];
    $roomId = $postData['room_type'];
    $roomType = typeOfRoom($roomId);
    $arrivalDate = $postData['start_date'];
    $departureDate = $postData['end_date'];

    if (isDepartureBeforeArrival($arrivalDate, $departureDate)) {
        return [
            "status" => "error",
            "message" => "Departure date cannot be before arrival date",
        ];
    } elseif (isRoomAvailable($roomType, $arrivalDate, $departureDate)) {
        writeToDatabase($postData);
        return [
            "status" => "success",
            "message" => "Booking successful",

        ];
    } else {
        return [
            "status" => "error",
            "message" => "Room is not available for the selected dates",
        ];
    }
}

function isDepartureBeforeArrival($arrivalDate, $departureDate)
{
    $arrival = DateTime::createFromFormat('Y-m-d', $arrivalDate);
    $departure = DateTime::createFromFormat('Y-m-d', $departureDate);

    if ($departure < $arrival) {
        return true;
    } else {
        return false;
    }
}


function isRoomAvailable($roomId, $arrivalDate, $departureDate)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM Bookings WHERE room_id = :room_id AND NOT (departure_date <= :arrival_date OR arrival_date >= :departure_date)");
    $stmt->execute([
        ':room_id' => $roomId,
        ':arrival_date' => $arrivalDate,
        ':departure_date' => $departureDate
    ]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($bookings);
    echo ($roomId . $arrivalDate . $departureDate);
    return count($bookings) === 0;
}



function writeToDatabase($bookingData)
{
    var_dump($bookingData);
    global $db;
    $name = $bookingData['name'];
    $roomId = $bookingData['room_type'];
    $arrivalDate = $bookingData['start_date'];
    $departureDate = $bookingData['end_date'];


    $bookGuest = $db->prepare("INSERT INTO Guests (name) VALUES (:name)");
    $bookGuest->execute([
        ':name' => $name
    ]);

    $guestId = $db->lastInsertId();
    $daysBooked = daysBookedCalc($arrivalDate, $departureDate);
    $roomType = typeOfRoom($roomId);
    $bookRoom = $db->prepare("INSERT INTO Bookings (guest_id, room_id, arrival_date, departure_date, total_cost) VALUES (:guest_id, :room_id, :arrival_date, :departure_date, 20)");
    $bookRoom->execute([
        ':guest_id' => $guestId,
        ':room_id' => $roomType,
        ':arrival_date' => $arrivalDate,
        ':departure_date' => $departureDate
    ]);
}

// ':room_id' => $roomId,
// ':total_cost' => $totalCost
//lägg till total_cost här så det står med i databasen. så man kan se vad kunden fick betala ifall man skulle ändra priset


function daysBookedCalc($start, $end)
{
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);

    $interval = $startDate->diff($endDate);

    $days = $interval->days + 1;


    return $days;
}

function typeOfRoom($room)
{
    if ($room == "cheap") {
        return 1;
    } elseif ($room == "medium") {
        return 2;
    } elseif ($room == "expensive") {
        return 3;
    }
}
