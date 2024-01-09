<?php



require '../app/views/header.php';
?>
<link rel="stylesheet" href="calenderCss.css">
</head>

<body>
    <?php
    require '../app/views/navbar.php';
    ?>
    <main>
        <section class="rooms">
            <h2> Välj ett av våra rum</h2>
            <ul class="cards">
                <li class="cards__item">
                    <a class="room-button" id="cheapRoomButton" hx-get="getBookings.php" hx-trigger="click" hx-target="#calendar" hx-vals='{"room_id": 1}' hx-swap="none">
                        <div class="card">
                            <div class="card__image card__image--cheap"></div>
                            <div class="card__content">
                                <div class="card__title">Flex</div>
                                <p class="card__text">This is the shorthand for flex-grow, flex-shrink and flex-basis combined. The second and third parameters (flex-shrink and flex-basis) are optional. Default is 0 1 auto. </p>
                                <p id="cheapRoomPrice"></p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="cards__item">
                    <a class="room-button" hx-get="getBookings.php" hx-trigger="click" hx-target="#calendar" hx-vals='{"room_id": 2}' hx-swap="none">
                        <div class="card">
                            <div class="card__image card__image--medium"></div>
                            <div class="card__content">
                                <div class="card__title">Flex Grow</div>
                                <p class="card__text">This defines the ability for a flex item to grow if necessary. It accepts a unitless value that serves as a proportion. It dictates what amount of the available space inside the flex container the item should take up.</p>
                                <p id="mediumRoomPrice"></p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="cards__item">
                    <a class="room-button" hx-get="getBookings.php" hx-trigger="click" hx-target="#calendar" hx-vals='{"room_id": 3}' hx-swap="none">
                        <div class="card">
                            <div class="card__image card__image--expensive"></div>
                            <div class="card__content">
                                <div class="card__title">Flex Shrink</div>
                                <p class="card__text">This defines the ability for a flex item to shrink if necessary. Negative numbers are invalid.</p>
                                <p id="expensiveRoomPrice"></p>
                            </div>
                        </div>
                    </a>
                </li>

            </ul>
        </section>

        <form id="dateSelectionForm">
            <div class="startDate">
                <label for="startDate">Start Date:</label>

                <input type="date" id="startDate" name="startDate" min="2024-01-01" max="2024-01-31" />
            </div>
            <div class="endDate">
                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" name="endDate" min="2024-01-01" max="2024-01-31" />
            </div>
        </form>

        <div id="calendar"></div>
        <form id="bookingForm">
            <h2>Boka paket</h2>
            <div id="activitiesContainer"></div>


            <input type="hidden" id="selectedRoomType" name="roomType" />
            <div id="bookingModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Complete Your Booking</h2>
                    <label for="guestName">Name:</label>
                    <input type="text" id="guestName" name="guestName" />

                    <label for="paymentKey">Payment API Key:</label>
                    <input type="text" id="paymentKey" name="paymentKey" />
                    <div id="totalCostDisplay"></div>
                    <button id="payButton">Pay</button>
                </div>
            </div>
            <button class="small-button" type="submit">Book Now</button>
        </form>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');

            cards.forEach(card => {
                card.addEventListener('click', function() {

                    cards.forEach(c => c.classList.remove('latest-clicked'));


                    this.classList.add('latest-clicked');
                });
            });
        });
    </script>
    <script src="/public/script.js"></script>
    <?php
    require '../app/views/footer.php';
    ?>