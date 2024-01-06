<?php

declare(strict_types=1);

session_start();
require '../database/connect.php';

if (empty($_SESSION['is_admin'])) {
    header('Location: ../../public/login.php');
    exit;
}

$feature_id = $_GET['feature_id'] ?? null;

if (!$feature_id) {
    exit('Feature ID is required.');
}

$stmt = $db->prepare("SELECT * FROM Hotel_Features WHERE feature_id = :feature_id");
$stmt->bindParam(':feature_id', $feature_id);
$stmt->execute();
$activity = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['activity_name'] ?? '';
    $description = $_POST['activity_description'] ?? '';
    $cost = $_POST['activity_cost'] ?? '';
    $image_url = $_POST['activity_image_url'] ?? '';

    $sql = "UPDATE Hotel_Features SET name = :name, description = :description, cost = :cost, image_url = :image_url WHERE feature_id = :feature_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':cost', $cost);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->bindParam(':feature_id', $feature_id);
    $stmt->execute();

    header('Location: admin.php');
    exit;
}

require '../views/header.php';
require '../views/navbar.php';
?>

<h1>Edit Activity</h1>
<form method="post" action="">
    <input type="text" name="activity_name" value="<?= htmlspecialchars($activity['name']) ?>" required>
    <textarea name="activity_description"><?= htmlspecialchars($activity['description']) ?></textarea>
    <input type="number" name="activity_cost" value="<?= htmlspecialchars((string)$activity['cost']) ?>" required>

    <input type="text" name="activity_image_url" value="<?= htmlspecialchars($activity['image_url']) ?>">
    <button type="submit">Update Activity</button>
</form>

<?php
require '../views/footer.php';
?>