<?php

declare(strict_types=1);

function checkRoomAvailability($db, string $startDate, string $endDate, string $roomTypeJson): bool
{

    $roomTypeArray = json_decode($roomTypeJson, true);
    $roomId = $roomTypeArray['room_id'];


    $sql = "SELECT COUNT(*) FROM Bookings
    WHERE room_id = :room_id
    AND (
        (:start_date BETWEEN arrival_date AND departure_date) OR
        (:end_date BETWEEN arrival_date AND departure_date) OR
        (arrival_date BETWEEN :start_date AND :end_date) OR
        (departure_date BETWEEN :start_date AND :end_date)
    )";


    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':room_id' => $roomId,
        ':start_date' => $startDate,
        ':end_date' => $endDate
    ]);


    $count = $stmt->fetchColumn();

    if ($count > 0) {

        return false;
    } else {

        return true;
    }
}
