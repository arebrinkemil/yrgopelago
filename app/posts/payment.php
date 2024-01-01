<?php

require __DIR__ . '/../../vendor/autoload.php';


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


function processPayment($transferCode, $totalCost)
{
    $client = new Client();



    try {

        error_log("Sending to transferCode API: transferCode: $transferCode, totalCost: $totalCost");

        $response = $client->request('POST', 'https://www.yrgopelag.se/centralbank/transferCode', [
            'form_params' => [
                'transferCode' => $transferCode,
                'totalcost' => $totalCost,
            ],
            'verify' => false,
        ]);

        $responseContent = json_decode($response->getBody(), true);


        if ($response->getStatusCode() == 200 && !isset($responseContent['error'])) {

            $depositResponse = $client->request('POST', 'https://www.yrgopelag.se/centralbank/deposit', [
                'form_params' => [
                    'user' => 'Emil',
                    'transferCode' => $transferCode,
                ],
                'verify' => false,
            ]);

            $depositResponseContent = json_decode($depositResponse->getBody(), true);

            if ($depositResponse->getStatusCode() == 200 && !isset($depositResponseContent['error'])) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Deposit failed: ' . ($depositResponseContent['error'] ?? 'Unknown error')];
            }
        } else {
            return ['success' => false, 'error' => 'Invalid transfer code: ' . ($responseContent['error'] ?? 'Unknown error')];
        }
    } catch (RequestException $e) {
        error_log("Request Exception: " . $e->getMessage());
        return ['success' => false, 'error' => 'Request Exception: ' . $e->getMessage()];
    }
}
