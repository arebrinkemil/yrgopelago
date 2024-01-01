<?php

try {
    $db = new PDO('sqlite:' . __DIR__ . '/hoteldatabase.db');


    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

    die("Connection failed: " . $e->getMessage());
}
