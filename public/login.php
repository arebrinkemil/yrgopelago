<?php
session_start();

$env = parse_ini_file(__DIR__ . '/../.env');


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


require '../app/views/header.php';

?>
<link rel="stylesheet" href="login.css">
</head>

<body>
    <?php
    require '../app/views/navbar.php';

    ?>
    <div class="login-container">

        <h1>Admin Login</h1>
        <?php if (!empty($error)) {
            echo "<p>$error</p>";
        } ?>
        <form method="post" action="">
            <label for="api_key">API Key:</label>
            <input type="password" id="api_key" name="api_key" required>
            <button type="submit">Login</button>
        </form>

    </div>
    <?php



    require '../app/views/footer.php';
    ?>