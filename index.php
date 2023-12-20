<?php

require_once __DIR__ . '/vendor/autoload.php';

use cerfaapp\controllers\App_config;
use cerfaapp\controllers\CerfaReceiptGen;
use cerfaapp\controllers\Router;
use cerfaapp\controllers\Request;
use cerfaapp\controllers\Response;
use cerfaapp\controllers\Cerfa;
use cerfaapp\controllers\RequestValidity;



// retreive the FDF files from templates
Router::get("/", function (Request $request, Response $response) {
    $date = new DateTime();
    $info = getenv('LANDO_INFO') ? json_decode(getenv('LANDO_INFO'), TRUE) : null;
    $templates = Cerfa::getTemplateFiles();

    $response->toJSON([
        'message' => 'hello world GET',
        'date' => $date->format('Y-m-d H:i:s'),
        'landoInfo' => $info,
        'templates' => $templates
    ]);
});


// use this route to generate the PDF
Router::post("/", function (Request $request, Response $response) {
    $date = new DateTime();

    $requestValidity = new RequestValidity();
    if (!$requestValidity($request)) {
        $response->toJSON([
            'message' => 'invalid request',
        ]);
    } else {
        // generate the PDF
        //$cerfa = new Cerfa($request->getJSON());

        // then send it
        $response->toJSON([
            'message' => 'is ok for prod ;)',
            'date' => $date->format('Y-m-d H:i:s'),
        ]);
    }

/*    $response->toJSON([
        't' => 'hello world POST',
        'date' => $date->format('Y-m-d H:i:s'),
        'reqMethod' => $request->getReqMethod(),
        'your content' => $content
    ]);*/
});


//require __DIR__ . '/cerfaapp/controllers/controller.php';

CerfaReceiptGen::run();
