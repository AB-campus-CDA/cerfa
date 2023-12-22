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
    //print_r('post received');

    $data = $request->getJSON('raw');
    $donorType = json_decode($data, true)['donor_type'];
    $fdf = Cerfa::checkValidity($donorType, $data);
    //var_dump($fdf['signature']);

    if (count($fdf) > 0) {

        $filledCerfa = Cerfa::generateReceipt($fdf, $donorType);

        // then send it
        $response->toJSON([
            'date' => $date->format('Y-m-d H:i:s'),
            //'fdf' => $fdf,
            //'sign' => $fdf['signature'],
            'receipt' => $filledCerfa
        ]);}
    else {
        $response->setStatus(400);
        $response->toJSON([
            'date' => $date->format('Y-m-d H:i:s'),
            'error' => 'invalid data'
        ]);
    }

    $response->setStatus(400);

});


//require __DIR__ . '/cerfaapp/controllers/controller.php';

CerfaReceiptGen::run();
