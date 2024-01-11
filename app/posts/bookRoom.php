<?php

require '../app/database/connect.php';
require '../app/autoload.php';



function bookRoomAndActivities($db, $guestName, $startDate, $endDate, $roomTypeJson, $activities)
{
    try {
        $db->beginTransaction();

        $roomTypeData = json_decode($roomTypeJson, true);
        $roomTypeId = $roomTypeData['room_id'];

        $stmt = $db->prepare("INSERT INTO Guests (name) VALUES (:name)");
        $stmt->execute([':name' => $guestName]);
        $guestId = $db->lastInsertId();

        $stmt = $db->prepare("INSERT INTO Bookings (guest_id, room_id, arrival_date, departure_date, total_cost) VALUES (:guest_id, :room_id, :arrival_date, :departure_date, :total_cost)");
        $stmt->execute([
            ':guest_id' => $guestId,
            ':room_id' => $roomTypeId,
            ':arrival_date' => $startDate,
            ':departure_date' => $endDate,
            ':total_cost' => $_SESSION['totalPrice']
        ]);
        $bookingId = $db->lastInsertId();

        if (!empty($activities)) {
            $stmt = $db->prepare("INSERT INTO Booking_Features (booking_id, feature_id) VALUES (:booking_id, :feature_id)");
            foreach ($activities as $featureName) {

                $featureStmt = $db->prepare("SELECT feature_id FROM Hotel_Features WHERE name = :name");
                $featureStmt->execute([':name' => $featureName]);
                $featureId = $featureStmt->fetchColumn();


                if ($featureId) {
                    $stmt->execute([
                        ':booking_id' => $bookingId,
                        ':feature_id' => $featureId
                    ]);
                } else {
                    throw new Exception("Feature not found: " . $featureName);
                }
            }
        }


        $db->commit();


        unset($_SESSION['totalPrice']);

        return ['success' => true, 'bookingId' => $bookingId, 'guestId' => $guestId];
    } catch (Exception $e) {

        $db->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
