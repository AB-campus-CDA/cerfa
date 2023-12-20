<?php

require_once __DIR__ . '/vendor/autoload.php';

use cerfaapp\controllers\App_config;
use cerfaapp\controllers\CerfaReceiptGen;
use cerfaapp\controllers\Router;
use cerfaapp\controllers\Request;
use cerfaapp\controllers\Response;
use cerfaapp\controllers\Cerfa;



// retreive the FDF files from templates
Router::get("/", function (Request $request, Response $response) {
    $date = new DateTime();
    $info = json_decode(getenv('LANDO_INFO'), TRUE);
    $templates = Cerfa::getTemplateFiles();

    $response->toJSON([
        'message' => 'hello world GET',
        'date' => $date->format('Y-m-d H:i:s'),
        'landoInfo' => $info,
        'templates' => $templates,
    ]);
});

/*
// use this route to generate the PDF
Router::post("/", function (Request $request, Response $response) {
    $date = new DateTime();
    $response->toJSON([
        't' => 'hello world POST',
        'date' => $date->format('Y-m-d H:i:s'),
        'reqMethod' => $request->getReqMethod(),
    ]);
});*/


//require __DIR__ . '/cerfaapp/controllers/controller.php';

CerfaReceiptGen::run();
