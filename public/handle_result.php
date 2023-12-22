<?php

session_start();

if (isset($_SESSION['result']) && isset($_SESSION['price'])) {
    $result = $_SESSION['result'];
    $price = $_SESSION['price'];

    echo "<p>" . htmlspecialchars($result['message']) . "</p>";

    // Check if the result message indicates that the room is available
    if ($result['message'] === "Room available for the selected dates") {
        echo "<p>Price: " . htmlspecialchars($price) . "</p>";
?>
        <form action="/public/booking_success.php" method="post">
            <input type="submit" value="Book the room">
        </form>
<?php
    }

    // Clear the session variables
    unset($_SESSION['result'], $_SESSION['price']);
} else {
    echo "No booking information available.";
}
?>