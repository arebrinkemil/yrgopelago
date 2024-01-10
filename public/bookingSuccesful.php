<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Information</title>
</head>

<body>
    <pre id="bookingInfo"></pre>

    <button id="copyButton" style="position: fixed; bottom: 10px; right: 10px;">Copy JSON</button>

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
</body>

</html>