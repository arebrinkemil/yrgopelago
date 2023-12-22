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
    <link rel="stylesheet" href="/public/style.css">
    <script src="https://unpkg.com/htmx.org"></script>
</head>

<body>
    <div class="container">
        <!-- HTMX room type selector -->
        <select id="room_type" name="room_type" required hx-get="../app/posts/booking_handler.php?room_type=" hx-trigger="change" hx-target="#calendar">
            <option value="Budget">Budget</option>
            <option value="Standard">Standard</option>
            <option value="Luxury">Luxury</option>
        </select>

        <h1>Booking Form</h1>
        <div id='calendar'></div>

        <form action="/public/index.php" method="post">
            <label for="name">First Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
            <input type="hidden" id="room_type_hidden" name="room_type">
            <input type="submit" value="Book">
        </form>

        <?php if (isset($result)) : ?>
            <p><?= $result['message'] ?></p>
            <p>Price: <?= $price ?></p>
            <form action="/public/booking_success.php" method="post">
                <input type="submit" value="Book the room">
            </form>
        <?php endif; ?>
    </div>

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

        var currentCalendar = null;

        document.getElementById('room_type').addEventListener('change', function() {
            var selectedRoomType = this.value;
            document.getElementById('room_type_hidden').value = selectedRoomType;
            initializeCalendar(selectedRoomType);
        });

        function initializeCalendar(roomType) {
            if (currentCalendar) {
                currentCalendar.destroy();
            }

            var calendarEl = document.getElementById('calendar');
            var selectingStartDate = true;
            var startDate, endDate;

            currentCalendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch('../app/posts/booked_dates.php?room_type=' + roomType)
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => failureCallback(error));
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
                            }, 10);
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
                            }, 10);
                        }


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
            currentCalendar.render();
        }

        document.body.addEventListener('htmx:afterSwap', function(event) {
            if (event.target.id === 'calendar') {
                var selectedRoomType = document.getElementById('room_type').value;
                initializeCalendar(selectedRoomType);
            }
        });


        // initializeCalendar('default_room_type');
    </script>

</body>

</html>