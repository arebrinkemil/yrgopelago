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
                    <a href="#calendar" class="room-button" id="cheapRoomButton" hx-get="getBookings.php" hx-trigger="click" hx-target="#calendar" hx-vals='{"room_id": 1}' hx-swap="none">
                        <div class="card">
                            <div class="card__image card__image--cheap"></div>
                            <div class="card__content">
                                <div class="card__title">Mysig Sjöbod</div>
                                <p class="card__text">Upptäck charmen i vår Mysiga Sjöbod, det perfekta valet för den prismedvetna resenären som inte vill kompromissa med kvalitet och upplevelse. Detta gemytligt inredda rum andas en rustik och marin atmosfär, perfekt för att koppla av efter en dag av upptäcktsfärder. Utrustad med bekväma sängar och utsikt över det lugna havet, erbjuder Sjöboden en fristad där du kan slappna av och lyssna till vågornas lugnande brus.</p>
                                <p id="cheapRoomPrice"></p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="cards__item">
                    <a href="#calendar" class="room-button" hx-get="getBookings.php" hx-trigger="click" hx-target="#calendar" hx-vals='{"room_id": 2}' hx-swap="none">
                        <div class="card">
                            <div class="card__image card__image--medium"></div>
                            <div class="card__content">
                                <div class="card__title">Vinga Fyr</div>
                                <p class="card__text">Bo i hjärtat av Vingas historia i vårt medeldyra rum, Vinga Fyr. Detta unika rum erbjuder en oslagbar kombination av komfort och historisk charm. Med sin autentiska inredning och moderna bekvämligheter, ger rummet dig en känsla av att vara en del av öns sjöfarararv. Vakna upp till en betagande utsikt över det oändliga havet och njut av den fridfulla atmosfären som bara en fyrmästare kunde uppleva.</p>
                                <p id="mediumRoomPrice"></p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="cards__item">
                    <a href="#calendar" class="room-button" hx-get="getBookings.php" hx-trigger="click" hx-target="#calendar" hx-vals='{"room_id": 3}' hx-swap="none">
                        <div class="card">
                            <div class="card__image card__image--expensive"></div>
                            <div class="card__content">
                                <div class="card__title">Lyxig Kaptengård</div>
                                <p class="card__text">För den mest kräsne gästen presenterar vi vår Lyxiga Kaptengård. Detta rum är en verklig tillflyktsort av lyx och komfort. Med sitt sofistikerade inredningskoncept som kombinerar traditionell elegans med moderna inslag, erbjuder Kaptengården en upplevelse utöver det vanliga. Njut av rummets exklusiva faciliteter, inklusive en privat terrass med utsikt över den storslagna skärgården. Vår Lyxiga Kaptengård är inte bara ett rum, det är en upplevelse som berikar din själ och förnyar ditt sinne.</p>
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
            <div id="bookingModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div class="booking-info">
                        <h2>Betalning</h2>
                        <label for="guestName">Namn:</label>
                        <input type="text" id="guestName" name="guestName" />

                        <label for="paymentKey">TransferCode:</label>
                        <input type="text" id="paymentKey" name="paymentKey" />
                        <div id="totalCostDisplay"></div>
                    </div>
                    <button id="payButton" style=" width: 100%;" class="small-button book-button" type="submit">Book Now</button>
                </div>
            </div>
            <button style="width: 100%;" class="small-button book-button" type="submit">Book Now</button>
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