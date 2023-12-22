<?php
require_once __DIR__ . '/process_booking.php';
require_once __DIR__ . '/handle_result.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['room_type'])) {
        $_SESSION['selected_room_type'] = $_POST['room_type']; // Save to session
    }
    $hotelData = checkHotel($_POST);
}

// Retrieve the selected room type from session if available
$selectedRoomType = isset($_SESSION['selected_room_type']) ? $_SESSION['selected_room_type'] : 'Budget';
?>



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
        <button class="room-type-button" data-room-type="Budget">Budget</button>
        <button class="room-type-button" data-room-type="Standard">Standard</button>
        <button class="room-type-button" data-room-type="Luxury">Luxury</button>

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

        <div id="booking_result" hx-get="handle_result.php" hx-trigger="load">
            <!-- The result will be loaded here -->
        </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            var defaultRoomType = '<?php echo $selectedRoomType; ?>';

            if (sessionStorage.getItem('selectedRoomType')) {
                defaultRoomType = sessionStorage.getItem('selectedRoomType');
            }

            document.getElementById('room_type_hidden').value = defaultRoomType;
            initializeCalendar(defaultRoomType);

            htmx.trigger('#booking_result', 'load');
        });



        var buttons = document.querySelectorAll('.room-type-button');
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                var roomType = this.dataset.roomType;
                document.getElementById('room_type_hidden').value = roomType;

                fetch('../app/posts/booking_handler.php?room_type=' + roomType)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('calendar').innerHTML = data;
                        initializeCalendar(roomType);
                        htmx.trigger('#booking_result', 'load');
                    });
            });
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
                var selectedRoomType = document.getElementById('room_type_hidden').value;
                initializeCalendar(selectedRoomType);
            }
        });
    </script>

</body>

</html>