<?php
require_once __DIR__ . '/process_booking.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotelData = checkHotel($_POST);
}
if (isset($_SESSION['result']) && isset($_SESSION['price'])) {
    $result = $_SESSION['result'];
    $price = $_SESSION['price'];

    unset($_SESSION['result'], $_SESSION['price']);
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Booking Form</title>
</head>

<body>
    <form action="/public/index.php" method="post">
        <label for="name">First Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>

        <label for="room_type">Room Type:</label>
        <select id="room_type" name="room_type" required>
            <option value="cheap">Cheap</option>
            <option value="medium">Medium</option>
            <option value="expensive">Expensive</option>
        </select>

        <input type="submit" value="Book">
    </form>

    <?php if (isset($result)) : ?>
        <p><?= $result['message'] ?></p>
        <p>Price: <?= $price ?></p>
        <form action="/public/booking.php" method="post">
            <input type="submit" value="Book the room">
        </form>

    <?php endif; ?>
</body>

</html>