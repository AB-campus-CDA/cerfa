<?php

use mikehaertl\pdftk\Pdf;

// generate from template
$pdf = new Pdf('/app/templates/cerfa_11580_05.pdf');

$result = $pdf
    ->needAppearances()
    ->generateFdfFile('/app/temporary_outputs/cerfa_11580_05.fdf');

if ($result === false) {
    $error = $pdf->getError();
    var_dump($error);
} else {
    var_dump($result);
}

$pdf2 = new Pdf('/app/templates/cerfa_11580_05.pdf');

$result2 = $pdf2->fillForm('/app/temporary_outputs/cerfa_11580_05.fdf')
    ->saveAs('/app/temporary_outputs/cerfa_11580_05.pdf');

if ($result2 === false) {
    $error = $pdf2->getError();
    var_dump($error);
} else {
    var_dump($result2);
}


