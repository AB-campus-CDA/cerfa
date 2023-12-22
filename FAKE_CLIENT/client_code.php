<?php

require '../vendor/autoload.php';

$apiUrl = "http://cerfaapp.lndo.site";

// generator new receipt number
$date = new DateTime();
$receiptNumber = substr(strval($date->getTimestamp()),0,8);

// unique api token
$apiToken = "hgukezg564ezr4ez5f4ez8r7gryh62bt1b8ry87h9u9yyyh4t5yh7e";

// data source
$sourcePath = __DIR__ . "/source/";

// path to the generated PDF
$filepath = __DIR__ . "/reçus/cerfa_$receiptNumber.pdf";

// base64 signature data
$sign = base64_encode(file_get_contents($sourcePath . 'signature.png') );



use GuzzleHttp\Client;

$client = new Client();

$response = $client->post($apiUrl, [
    'headers' => [
        'Accept' => "application/json",
        'Content-Type' => "application/json",
        //'Authorization' => "Bearer $apiToken",
    ],
    'body' => json_encode([
        'donor_type'       => 'INDIVIDUAL',
        'number'     => $receiptNumber,
        'amount'     => 123.45,
        'asso_name'  => "ASPAS",
        'asso_siren' => "377831474", // @see https://annuaire-entreprises.data.gouv.fr/entreprise/protect-animaux-sauvages-patrim-naturel-aspas-377831474
        'asso_type'  => "LOI1901",
        'signature'  => $sign,
    ])
]);

var_dump($response->getStatusCode());
$body = $response->getBody();
$content = $body->getContents();
var_dump($content);
print_r('---------------------------------------' . PHP_EOL);
print_r('---------------------------------------' . PHP_EOL);
var_dump(json_decode($content, true)['receipt']);

if ($result = json_decode($response->getBody(), true)) {
    // Output the generated PDF to file
    file_put_contents($filepath, base64_decode(json_decode($content, true)['receipt']));
}


