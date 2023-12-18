<!DOCTYPE html>
<html>

<head>
    <link href='https://unpkg.com/fullcalendar@5/main.min.css' rel='stylesheet' />
    <script src='https://unpkg.com/fullcalendar@5/main.min.js'></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id='calendar'></div>

    <!-- Your other HTML goes here -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
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
                }

            });

            calendar.render();
        });
    </script>

</body>

</html>