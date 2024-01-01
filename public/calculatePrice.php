<?php

header('Content-Type: application/json');
require '../app/database/connect.php';
require '../app/autoload.php';



function calculateDays($startDate, $endDate)
{
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $end->modify('+1 day');
    return $start->diff($end)->days;
}

function getRoomPrice($db, $roomType)
{
    $stmt = $db->prepare("SELECT price FROM Rooms WHERE room_id = ?");
    $stmt->execute([$roomType]);
    return $stmt->fetchColumn();
}

function getFeatureCost($db, $featureName)
{
    $stmt = $db->prepare("SELECT cost FROM Hotel_Features WHERE name = ?");
    $stmt->execute([$featureName]);
    return $stmt->fetchColumn();
}


unset($_SESSION['totalPrice']);

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}
$roomTypeJson = json_decode($input['roomType'], true);
$roomType = $roomTypeJson['room_id'];

$startDate = $input['startDate'];
$endDate = $input['endDate'];
$activities = $input['activities'];

$totalDays = calculateDays($startDate, $endDate);

$roomPricePerNight = getRoomPrice($db, $roomType);
if (!$roomPricePerNight) {
    echo json_encode(['error' => 'Invalid room type']);
    exit;
}

$roomCost = $totalDays * $roomPricePerNight;
$featuresCost = 0;

foreach ($activities as $activity) {
    $featureCost = getFeatureCost($db, $activity);
    if (!$featureCost) {
        echo json_encode(['error' => "Invalid activity: $activity"]);
        exit;
    }
    $featuresCost += $featureCost;
}

$totalPrice = $roomCost + $featuresCost;


$_SESSION['totalPrice'] = $totalPrice;


echo json_encode(['totalPrice' => $totalPrice]);