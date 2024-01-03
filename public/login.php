<?php
session_start();

$env = parse_ini_file(__DIR__ . '/../.env');
echo $env['API_KEY'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputApiKey = $_POST['api_key'];

    if ($inputApiKey == $env['API_KEY']) {
        $_SESSION['is_admin'] = true;

        header('Location: ../app/admin/admin.php');
        exit;
    } else {
        $error = "Invalid API Key.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
</head>

<body>
    <h1>Admin Login</h1>
    <?php if (!empty($error)) {
        echo "<p>$error</p>";
    } ?>
    <form method="post" action="">
        <label for="api_key">API Key:</label>
        <input type="password" id="api_key" name="api_key" required>
        <button type="submit">Login</button>
    </form>
</body>

</html>