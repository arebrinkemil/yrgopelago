<?php
session_start();
require '../database/connect.php';

if (empty($_SESSION['is_admin'])) {
    header('Location: ../../public/login.php');
    exit;
}

function addActivity($name, $description, $cost, $image_url, $db)
{
    $sql = "INSERT INTO Hotel_Features (name, description, cost, image_url) VALUES (:name, :description, :cost, :image_url)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':cost', $cost);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->execute();
}

// Function to update room prices
function updateRoomPrice($room_id, $price, $db)
{
    $sql = "UPDATE Rooms SET price = :price WHERE room_id = :room_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':room_id', $room_id);
    $stmt->execute();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_activity'])) {
    $name = $_POST['activity_name'];
    $description = $_POST['activity_description'];
    $cost = $_POST['activity_cost'];
    $image_url = $_POST['activity_image_url'];

    addActivity($name, $description, $cost, $image_url, $db);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_prices'])) {
    foreach ($_POST['rooms'] as $room_id => $price) {
        updateRoomPrice($room_id, $price, $db);
    }
}
function getAllBookingsWithActivities($db)
{
    $sql = "SELECT
                Bookings.booking_id,
                Guests.name AS guest_name,
                Rooms.room_type,
                Bookings.arrival_date,
                Bookings.departure_date,
                Bookings.total_cost,
                GROUP_CONCAT(Hotel_Features.name) AS activities
            FROM Bookings
            JOIN Guests ON Bookings.guest_id = Guests.guest_id
            JOIN Rooms ON Bookings.room_id = Rooms.room_id
            LEFT JOIN Booking_Features ON Bookings.booking_id = Booking_Features.booking_id
            LEFT JOIN Hotel_Features ON Booking_Features.feature_id = Hotel_Features.feature_id
            GROUP BY Bookings.booking_id";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$bookingsWithActivities = getAllBookingsWithActivities($db);

function getAllActivities($db)
{
    $sql = "SELECT * FROM Hotel_Features";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all activities
$activities = getAllActivities($db);



require '../views/header.php';

require '../views/navbar.php';

?>
<h1>Admin Panel</h1>
<section>
    <h2>Activities</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Cost</th>
                <th>Image URL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activities as $activity) : ?>
                <tr>
                    <td><?= htmlspecialchars($activity['name']) ?></td>
                    <td><?= htmlspecialchars($activity['description']) ?></td>
                    <td><?= htmlspecialchars($activity['cost']) ?></td>
                    <td><img class="admin_activity_img" src="../../public/images/<?php echo htmlspecialchars($activity['image_url']) ?>"></td>
                    <td><a href="edit_activity.php?feature_id=<?= htmlspecialchars($activity['feature_id']) ?>">Edit</a></td>
                    <td><a href="delete_activity.php?feature_id=<?= htmlspecialchars($activity['feature_id']) ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Add Activity</h2>
    <form method="post" action="">
        <input type="text" name="activity_name" placeholder="Activity Name" required>
        <textarea name="activity_description" placeholder="Activity Description"></textarea>
        <input type="number" name="activity_cost" placeholder="Cost" required>
        <input type="text" name="activity_image_url" placeholder="Image URL">
        <button type="submit" name="add_activity">Add Activity</button>
    </form>
</section>

<section>
    <h2>Update Room Prices</h2>
    <form method="post" action="">
        <?php
        $stmt = $db->query("SELECT room_id, room_type, price FROM Rooms");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div>";
            echo "<label>{$row['room_type']}</label>";
            echo "<input type='number' name='rooms[{$row['room_id']}]' value='{$row['price']}' required>";
            echo "</div>";
        }
        ?>
        <button type="submit" name="update_prices">Update Prices</button>
    </form>
</section>
<section>
    <h2>All Bookings with Activities</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Guest Name</th>
                <th>Room Type</th>
                <th>Arrival Date</th>
                <th>Departure Date</th>
                <th>Total Cost</th>
                <th>Booked Activities</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookingsWithActivities as $booking) : ?>
                <tr>
                    <td><?= htmlspecialchars($booking['booking_id']) ?></td>
                    <td><?= htmlspecialchars($booking['guest_name']) ?></td>
                    <td><?= htmlspecialchars($booking['room_type']) ?></td>
                    <td><?= htmlspecialchars($booking['arrival_date']) ?></td>
                    <td><?= htmlspecialchars($booking['departure_date']) ?></td>
                    <td><?= htmlspecialchars($booking['total_cost']) ?></td>
                    <td><?= htmlspecialchars($booking['activities']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php
require '../views/footer.php';
?>