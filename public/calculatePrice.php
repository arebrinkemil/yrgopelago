<?php

declare(strict_types=1);

header('Content-Type: application/json');
require '../app/database/connect.php';
require '../app/autoload.php';

function calculateDays(string $startDate, string $endDate): int
{
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $end->modify('+1 day');
    return $start->diff($end)->days;
}

function getRoomPrice(PDO $db, int $roomType): float
{
    $stmt = $db->prepare("SELECT price FROM Rooms WHERE room_id = ?");
    $stmt->execute([$roomType]);
    return (float) $stmt->fetchColumn();
}

function getFeatureCost(PDO $db, string $featureName): float
{
    $stmt = $db->prepare("SELECT cost FROM Hotel_Features WHERE name = ?");
    $stmt->execute([$featureName]);
    return (float) $stmt->fetchColumn();
}

$_SESSION['totalPrice'] = 0;

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$roomTypeJson = json_decode($input['roomType'], true);
$roomType = (int) $roomTypeJson['room_id'];

$startDate = (string) $input['startDate'];
$endDate = (string) $input['endDate'];
$activities = (array) $input['activities'];

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

$discount = 0;
if ($totalDays >= 3) {
    $discount = 0.3 * ($roomCost + $featuresCost);
}

$totalPrice = $roomCost + $featuresCost - $discount;

$totalPrice = round($totalPrice);

$_SESSION['totalPrice'] = $totalPrice;

echo json_encode(['totalPrice' => $totalPrice]);
