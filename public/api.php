<?php
session_start();
require '../vendor/autoload.php';
require_once __DIR__ . '/process_booking.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["transferCode"])) {
        handleInput($_POST["transferCode"]);
    }
}

function handleInput($transferCode)
{

    var_dump($transferCode);
    var_dump($_SESSION['price']);
    $totalCost = $_SESSION['price'];
    $client = new Client();
    try {
        $response = $client->request('POST', 'https://www.yrgopelag.se/centralbank/transferCode', [
            'form_params' => [
                'transferCode' => $transferCode,
                'totalcost' => $totalCost,
            ],
            'verify' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody();
        $content = $body->getContents();

        echo "Response status code: " . $statusCode . "\n";
        echo "Response body: " . $content . "\n";


        if ($statusCode == 200) {
            $response = $client->request('POST', 'https://www.yrgopelag.se/centralbank/deposit', [
                'form_params' => [
                    'user' => 'Emil',
                    'transferCode' => $transferCode,
                ],
                'verify' => false,
            ]);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $content = $body->getContents();

            echo "Response status code: " . $statusCode . "\n";
            echo "Response body: " . $content . "\n";
            if ($statusCode == 200) {
                header('Location: booking_success.php');
                exit;
            }
        }
    } catch (RequestException $e) {
        echo "An error occurred: " . $e->getMessage() . "\n";
        if ($e->hasResponse()) {
            echo "Error response: " . $e->getResponse()->getBody() . "\n";
        }
    }
}
