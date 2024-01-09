<?php

require '../app/views/header.php';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.0.2/css/glide.core.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.0.2/css/glide.theme.css" rel="stylesheet">
<link rel="stylesheet" href="index.css">
</head>

<body>
    <?php

    require '../app/views/navbar.php';
    ?>



    <main>
        <section class="hero">
            <div class="container grid">
                <div id="hero-container">
                    <p>Vinga Hotell</p>
                    <h1 class="title">Upptäck Vingas skönhet.<br>Din kustnära oas</h1>
                    <p>Lorem ipsum</p>
                </div>

                <div id="hero-image">
                    <img src="https://source.unsplash.com/bnt4w5jqgmo" width="100%" height="100%" />
                    <button class="small-button">
                        Boka här →
                    </button>
                </div>
            </div>
        </section>

        <div class="banner">
            <div class="text-section">
                <h1>Spara Mer På Din Vistelse!</h1>
                <p class="light italic bold">Boka Minst Tre Dagar och Få 30% Rabatt
                </p>
                <p class="light">
                    Upptäck vårt fantastiska erbjudande! När du bokar en vistelse på tre dagar eller längre hos oss, belönas du med en otrolig rabatt på 30%. Det är det perfekta tillfället att förlänga din semester och njuta av mer för mindre. Missa inte chansen att skapa oförglömliga minnen samtidigt som du sparar pengar. Boka din resa idag!
                </p>

            </div>
            <div class="image-section">

                <img src="/public/images/vingafyr.png" alt="Banner Image">
            </div>
        </div>

        <section class="hotel-rooms tight">
            <h3 class="top-left-text">Våra rum</h3>
            <div class="room-section">
                <ul class="room-list">
                    <li data-room="sjobod">Mysig Sjöbod</li>
                    <li data-room="fyrtorn">Vinga Fyr</li>
                    <li data-room="kaptengard">Lyxig Kaptengård</li>

                </ul>
                <div class="image-container">
                    <img src="images/sjobod.png" alt="Room Image" id="roomImage">

                </div>
            </div>
        </section>
        <section class="image-carousel wide">
            <div class="glide heropeek">
                <div class="glide__track" data-glide-el="track">
                    <ul class="glide__slides">
                        <img class="glide__slide" src="images/ocean-1.jpg">
                        <img class="glide__slide" src="images/wave.jpg">
                        <img class="glide__slide" src="images/ocean-2.jpg">
                        <img class="glide__slide" src="images/ocean.jpg">
                        <img class="glide__slide" src="images/ocean-3.jpg">
                        <img class="glide__slide" src="images/ocean-1.jpg">
                        <img class="glide__slide" src="images/wave.jpg">
                        <img class="glide__slide" src="images/ocean-2.jpg">
                        <img class="glide__slide" src="images/ocean.jpg">
                        <img class="glide__slide" src="images/ocean-3.jpg">
                    </ul>
                </div>

            </div>

        </section>
        <section class="about wide">
            <h3 class="top-left-text">Våra paket</h3>

            <div id="activitiesContainer"></div>




        </section>
        <section class="specials">
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var roomListItems = document.querySelectorAll('.room-list li');
            roomListItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    roomListItems.forEach(function(i) {
                        i.classList.remove('active');
                    });
                    this.classList.add('active');

                    var roomType = this.getAttribute('data-room');
                    var imagePath = 'images/' + roomType + '.png';

                    document.getElementById('roomImage').src = imagePath;
                });
            });

            var sjobodElement = document.querySelector('.room-list li[data-room="sjobod"]');
            if (sjobodElement) {
                sjobodElement.click();
            }



            fetch('getActivities.php')
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 'success') {
                        createActivityCheckboxes(data.activities);
                    } else {
                        console.error('Error fetching activities:', data.message);
                    }
                })
                .catch((error) => console.error('Error:', error));
        });

        function createActivityCheckboxes(activities) {
            const container = document.getElementById('activitiesContainer');
            container.classList.add('grid-container');

            activities.forEach((activity) => {
                const activityWrapper = document.createElement('div');
                activityWrapper.classList.add('grid-item', 'activity-wrapper');

                const gridContent = document.createElement('div');
                gridContent.classList.add('grid-content');

                const h3 = document.createElement('h3');
                h3.classList.add('activity-h3');
                h3.textContent = `${activity.name} - $${activity.cost} `;


                const description = document.createElement('p');
                description.classList.add('activity-description');
                description.textContent = activity.description.length > 100 ? activity.description.substring(0, 100) + "..." : activity.description;


                gridContent.appendChild(h3);

                gridContent.appendChild(description);

                activityWrapper.appendChild(gridContent);

                container.appendChild(activityWrapper);
            });
        }




        var glideHeroPeek = new Glide('.heropeek', {
            type: 'carousel',
            animationDuration: 1000,
            autoplay: 3000,
            focusAt: 'center',
            startAt: 1,
            perView: 1,
            peek: {
                before: 20,
                after: 20
            },
            gap: 0
        });



        glideHeroPeek.mount();
    </script>

</body>

</html>


<?php
require '../app/views/footer.php';

?>