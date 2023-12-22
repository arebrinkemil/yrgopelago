<?php
require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$client = new Client();
$transferCode = '2f388e61-c8b1-473c-be48-861e259c990c';
$totalCost = '2';
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
    }
} catch (RequestException $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
    if ($e->hasResponse()) {
        echo "Error response: " . $e->getResponse()->getBody() . "\n";
    }
}
