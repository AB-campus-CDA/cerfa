<?php

use pdftk\Pdf;

echo('test');
//$pdf = new Pdf();
// Get data
$pdf = new Pdf(__DIR__ . '/../../templates/cerfa_11580_05.pdf');

$data = $pdf->getDataFields(true);



// Create FDF from PDF

$result = $pdf
    ->needAppearances()
    ->generateFdfFile(__DIR__ . '/../../temporary_outputs/cerfa_11580_05.fdf');

/*if ($result === false) {
    $error = $pdf->getError();
    var_dump($error);
} else {*/
    //var_dump($result);
    print_r($data);
//}
