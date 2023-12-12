<?php

require '../app/posts/booking_handler.php';
require '../app/posts/price_calculator.php';
require '../app/autoload.php';


function checkHotel($postData)
{
    $_SESSION['post'] = $postData;
    $result = handleBooking($_POST);
    $price = checkPrice($_POST);
    $_SESSION['result'] = $result;
    $_SESSION['price'] = $price;
}
