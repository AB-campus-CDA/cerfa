<?php

require_once __DIR__ . '/../vendor/autoload.php';

use cerfaapp\controllers\App_config;
use cerfaapp\controllers\CerfaReceiptGen;
use cerfaapp\controllers\Router;
use cerfaapp\controllers\Request;
use cerfaapp\controllers\Response;



Router::get("/", function (Request $request, Response $response) {
    $date = new DateTime();
    $response->toJSON([
        't' => 'hello world GET',
        'date' => $date->format('Y-m-d H:i:s'),
        'reqMethod' => $request->getReqMethod(),
    ]);
});
Router::post("/", function (Request $request, Response $response) {
    $date = new DateTime();
    $response->toJSON([
        't' => 'hello world POST',
        'date' => $date->format('Y-m-d H:i:s'),
        'reqMethod' => $request->getReqMethod(),
    ]);
});



CerfaReceiptGen::run();
