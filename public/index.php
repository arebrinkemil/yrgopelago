<!DOCTYPE html>
<html>

<head>
    <title>Booking Form</title>
</head>

<body>
    <form action="/public/process_booking.php" method="post">
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
</body>

</html>