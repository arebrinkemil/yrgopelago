<?php

declare(strict_types=1);
session_start();
require '../database/connect.php';

if (empty($_SESSION['is_admin'])) {
    header('Location: ../../public/login.php');
    exit;
}

function addActivity(string $name, string $description, float $cost, string $image_url, PDO $db): void
{
    $sql = "INSERT INTO Hotel_Features (name, description, cost, image_url) VALUES (:name, :description, :cost, :image_url)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':cost', $cost);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->execute();
}

function deleteActivity(int $feature_id, PDO $db): void
{
    $stmt = $db->prepare("DELETE FROM Hotel_Features WHERE feature_id = :feature_id");
    $stmt->bindParam(':feature_id', $feature_id);
    $stmt->execute();
}

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $feature_id = (int) $_GET['delete'];
    deleteActivity($feature_id, $db);
    header("Location: admin.php");
    exit();
}


function updateRoomPrice(int $room_id, float $price, PDO $db): void
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
    $cost = (float) $_POST['activity_cost'];

    if (isset($_FILES['activity_image']) && $_FILES['activity_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['activity_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid() . "." . $filetype;
            $destination = '../../public/images/' . $new_filename;

            if (move_uploaded_file($_FILES['activity_image']['tmp_name'], $destination)) {

                $image_url = $new_filename;
            } else {

                echo "Error: Failed to upload the image.";
                exit;
            }
        } else {

            echo "Error: Invalid file type.";
            exit;
        }
    } else {

        echo "Error: No file uploaded.";
        exit;
    }

    addActivity($name, $description, $cost, $image_url, $db);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_prices'])) {
    foreach ($_POST['rooms'] as $room_id => $price) {
        updateRoomPrice((int) $room_id, (float) $price, $db);
    }
}
function getAllBookingsWithActivities(PDO $db): array
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

function getAllActivities(PDO $db): array
{
    $sql = "SELECT * FROM Hotel_Features";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all activities
$activities = getAllActivities($db);



require '../views/header.php';
?>
<link rel="stylesheet" href="admin.css">
</head>

<body>

    <?php


    require '../views/navbar.php';

    ?>
    <main>
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
                            <td><?= htmlspecialchars((string) $activity['name']) ?></td>
                            <td><?= htmlspecialchars((string) $activity['description']) ?></td>
                            <td><?= htmlspecialchars((string) $activity['cost']) ?></td>
                            <td><img class="admin_activity_img" src="../../public/images/<?php echo htmlspecialchars($activity['image_url']) ?>"></td>
                            <td><a href="edit_activity.php?feature_id=<?= $activity['feature_id'] ?>">Edit</a></td>
                            <td><a href="admin.php?delete=<?= $activity['feature_id'] ?>">Delete</a></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2>Add Activity</h2>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="text" name="activity_name" placeholder="Activity Name" required>
                <textarea name="activity_description" placeholder="Activity Description"></textarea>
                <input type="number" name="activity_cost" placeholder="Cost" required>
                <input type="file" name="activity_image" required>
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
                            <td><?= htmlspecialchars((string) $booking['booking_id']) ?></td>
                            <td><?= htmlspecialchars((string) $booking['guest_name']) ?></td>
                            <td><?= htmlspecialchars((string) $booking['room_type']) ?></td>
                            <td><?= htmlspecialchars((string) $booking['arrival_date']) ?></td>
                            <td><?= htmlspecialchars((string) $booking['departure_date']) ?></td>
                            <td><?= htmlspecialchars((string) $booking['total_cost']) ?></td>
                            <td><?= htmlspecialchars((string) $booking['activities']) ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php
    require '../views/footer.php';
    ?>