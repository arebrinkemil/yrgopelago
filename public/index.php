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
    <link href='https://unpkg.com/fullcalendar@5/main.min.css' rel='stylesheet' />
    <script src='https://unpkg.com/fullcalendar@5/main.min.js'></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id='calendar'></div>

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
        <form action="/public/booking_success.php" method="post">
            <input type="submit" value="Book the room">
        </form>
    <?php endif; ?>

    <script>
        function getDatesInRange(startDate, endDate) {
            var dateArray = [];
            var currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                dateArray.push(new Date(currentDate));
                currentDate.setDate(currentDate.getDate() + 1);
            }
            return dateArray;
        }


        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var selectingStartDate = true;
            var startDate, endDate;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                // ... other options ...
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch('../app/posts/booked_dates.php')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data); // Log the data
                            successCallback(data); // Provide the data to FullCalendar
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            failureCallback(error);
                        });
                },

                dateClick: function(info) {
                    // Clear previous selections
                    document.querySelectorAll('.start-date-selected, .end-date-selected, .range-date, .fade-out').forEach(function(el) {
                        el.classList.remove('start-date-selected', 'end-date-selected', 'range-date', 'fade-out');
                    });

                    if (selectingStartDate) {
                        startDate = new Date(info.dateStr);
                        document.getElementById('start_date').value = info.dateStr;
                        selectingStartDate = false;

                        var startCell = document.querySelector(`[data-date="${info.dateStr}"]`);
                        if (startCell) {
                            startCell.classList.add('start-date-selected');
                            setTimeout(function() {
                                startCell.classList.add('fade-out');
                            }, 10); // Short delay to ensure the transition is applied
                        }
                    } else {
                        endDate = new Date(info.dateStr);
                        document.getElementById('end_date').value = info.dateStr;
                        selectingStartDate = true;

                        var endCell = document.querySelector(`[data-date="${info.dateStr}"]`);
                        if (endCell) {
                            endCell.classList.add('end-date-selected');
                            setTimeout(function() {
                                endCell.classList.add('fade-out');
                            }, 10); // Short delay to ensure the transition is applied
                        }

                        // Get dates in range and apply class
                        var datesInRange = getDatesInRange(startDate, endDate);
                        datesInRange.forEach(function(date) {
                            var dateString = date.toISOString().split('T')[0];
                            var cell = document.querySelector(`[data-date="${dateString}"]`);
                            if (cell) {
                                cell.classList.add('range-date');
                            }
                        });
                    }
                }
            });
            calendar.render();
        });
    </script>
</body>

</html>