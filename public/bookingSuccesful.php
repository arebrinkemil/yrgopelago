<?php

require '../app/views/header.php';
?>

<link rel="stylesheet" href="receipt.css">
</head>

<body>
    <?php

    require '../app/views/navbar.php';
    ?>

    <div id="bookingReceipt"></div>

    <pre id="bookingInfo"></pre>
    <button id="copyButton">Copy JSON</button>

    <div class="boat">
        <h3>Båt upphämtning</h3>
        <p>Vi erbjuder båt upphämtning från Lilla Bommen, Göteborg. Båten avgår från Lilla Bommen kl 10:00 och 14:00. Båten avgår från Vinga kl 11:00 och 15:00. Båtturen tar ca 1 timme.</p>

        <div id="map"></div>
    </div>
    <script>
        const bookingInfo = JSON.parse(localStorage.getItem('bookingInfo') || '{}');
        console.log(bookingInfo);

        document.getElementById('bookingInfo').textContent = JSON.stringify(bookingInfo, null, 2);

        document.getElementById('copyButton').addEventListener('click', () => {
            navigator.clipboard.writeText(JSON.stringify(bookingInfo, null, 2))
                .then(() => alert('JSON copied to clipboard!'))
                .catch(err => console.error('Error copying JSON:', err));
        });
    </script>

    <script>
        const bookingReceipt = JSON.parse(localStorage.getItem('bookingInfo') || '{}');
        delete bookingReceipt.status;
        document.getElementById('bookingInfo').textContent = JSON.stringify(bookingReceipt, null, 2);

        function displayReceipt(info) {
            const receiptElement = document.getElementById('bookingReceipt');
            const imageUrl = info.additional_info.imageUrl;

            let receiptHtml = `
                <h1>Tack för din bokning!</h1>
                <img src="${imageUrl}" alt="Hotel Image" style="max-width:500px;width:100%;height:auto;">
                <h2>${info.hotel}</h2>
                <p><strong>Från:</strong> ${info.arrival_date}</p>
                <p><strong>Till:</strong> ${info.departure_date}</p>
                <p><strong>Pris:</strong> ${info.total_cost || 'Not specified'}</p>
                <p><strong></strong> ${'★'.repeat(info.stars)}</p>
                <h3>Tillägg:</h3>
                <ul>
                    ${info.features.map(feature => `<li>${feature}</li>`).join('')}
                </ul>
                <p><strong>Bokning ID:</strong> ${info.additional_info.bookingId}</p>
                <p><strong>Gäst ID:</strong> ${info.additional_info.guestId}</p>
            `;
            receiptElement.innerHTML = receiptHtml;
        }

        displayReceipt(bookingReceipt);
    </script>

    <script>
        function initMap() {
            var vinga = {
                lat: 57.712169,
                lng: 11.965874
            };
            var map = new google.maps.Map(
                document.getElementById('map'), {
                    zoom: 16,
                    center: vinga
                });
            var marker = new google.maps.Marker({
                position: vinga,
                map: map
            });
        }
    </script>


    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxCrBnSzhHbdYJcImfjPrc2NqrdXX0cS8&callback=initMap">
    </script>
</body>

</html>