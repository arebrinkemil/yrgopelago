<?php

require '../app/views/header.php';

require '../app/views/navbar.php';
?>
<section>
    <button id="cheapRoomButton" hx-get="getBookings.php" hx-trigger="click" hx-target="#calendar" hx-vals='{"room_id": 1}' hx-swap="none">
        Cheap Room
    </button>

    <button hx-get="getBookings.php" hx-trigger="click" hx-target="#calendar" hx-vals='{"room_id": 2}' hx-swap="none">
        Standard Room
    </button>
    <button hx-get="getBookings.php" hx-trigger="click" hx-target="#calendar" hx-vals='{"room_id": 3}' hx-swap="none">
        Luxury Room
    </button>
    <form id="dateSelectionForm">
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate" />

        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="endDate" />
    </form>

    <form id="bookingForm">
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
        <button type="submit">Book Now</button>
        <label>Extra Activities:</label>
        <div id="activitiesContainer"></div>
    </form>

    <div id="calendar"></div>
</section>

<script src="/public/script.js"></script>

<?php
require '../app/views/footer.php';
?>