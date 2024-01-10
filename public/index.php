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
            <p id="roomDescription">Select a room to see its description.</p>
        </section>
        <section class="image-carousel wide">

            <p>Upptäck Vingas rika historia – en ö som är en del av Göteborgs skärgård och har varit ett viktigt landmärke för sjöfarare i århundraden. Vinga är känt för sin berömda fyr, en av Sveriges mest ikoniska, som har väglett sjömän sedan 1800-talet. Ön har en fascinerande historia, från dess tidiga dagar som fiskarsamhälle till dess nuvarande status som en pittoresk turistdestination. Promenera längs de smala stigarna och känn historiens vingslag i varje steg.</p>
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
            <p>Njut av avkoppling och äventyr på Vinga. Vårt hotell erbjuder inte bara komfort och avkoppling, utan också en mängd aktiviteter för den äventyrlige. Utforska öns naturlandskap genom vandring eller delta i guidade turer för att lära dig mer om Vingas unika flora och fauna. För de som älskar havet, erbjuder vi kajakpaddling och fisketurer. Efter en dag full av äventyr, koppla av i vårt spa eller njut av en god bok i vår mysiga lounge.</p>

            <div id="activitiesContainer"></div>




        </section>
        <section class="specials">
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>
    <script>
        const roomDescriptions = {
            sjobod: "Upptäck charmen i vår Mysiga Sjöbod, det perfekta valet för den prismedvetna resenären som inte vill kompromissa med kvalitet och upplevelse. Detta gemytligt inredda rum andas en rustik och marin atmosfär, perfekt för att koppla av efter en dag av upptäcktsfärder. Utrustad med bekväma sängar och utsikt över det lugna havet, erbjuder Sjöboden en fristad där du kan slappna av och lyssna till vågornas lugnande brus.",
            fyrtorn: "Bo i hjärtat av Vingas historia i vårt medeldyra rum, Vinga Fyr. Detta unika rum erbjuder en oslagbar kombination av komfort och historisk charm. Med sin autentiska inredning och moderna bekvämligheter, ger rummet dig en känsla av att vara en del av öns sjöfarararv. Vakna upp till en betagande utsikt över det oändliga havet och njut av den fridfulla atmosfären som bara en fyrmästare kunde uppleva.",
            kaptengard: "För den mest kräsne gästen presenterar vi vår Lyxiga Kaptengård. Detta rum är en verklig tillflyktsort av lyx och komfort. Med sitt sofistikerade inredningskoncept som kombinerar traditionell elegans med moderna inslag, erbjuder Kaptengården en upplevelse utöver det vanliga. Njut av rummets exklusiva faciliteter, inklusive en privat terrass med utsikt över den storslagna skärgården. Vår Lyxiga Kaptengård är inte bara ett rum, det är en upplevelse som berikar din själ och förnyar ditt sinne."
        };

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

                    var roomType = this.getAttribute('data-room');
                    var descriptionText = roomDescriptions[roomType];
                    document.getElementById('roomDescription').textContent = descriptionText;
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
            console.log(activities);
            const container = document.getElementById('activitiesContainer');
            container.classList.add('grid-container');

            activities.forEach((activity) => {
                const activityWrapper = document.createElement('div');
                activityWrapper.classList.add('grid-item', 'activity-wrapper');

                const image = document.createElement('img');

                image.src = 'images/' + activity.image_url;
                image.alt = activity.name;
                image.classList.add('activity-image');

                const gridContent = document.createElement('div');
                gridContent.classList.add('grid-content');

                const h3 = document.createElement('h3');
                h3.classList.add('activity-h3');
                h3.textContent = `${activity.name} - $${activity.cost}`;

                const description = document.createElement('p');
                description.classList.add('activity-description');
                description.textContent = activity.description;

                gridContent.appendChild(h3);
                gridContent.appendChild(description);

                activityWrapper.appendChild(image);
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