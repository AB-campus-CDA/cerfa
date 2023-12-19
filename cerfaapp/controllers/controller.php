<?php

use mikehaertl\pdftk\Pdf;


// Get data
$pdf = new Pdf(__DIR__ . '/templates/cerfa_11580_05.pdf', [
    'command' => "/usr/bin/pdftk"
]);
$data = $pdf->getData();
print_r($pdf);

$HOME = getenv('HOME');
var_dump($HOME);

// Create FDF from PDF
$pdf = new Pdf(__DIR__ . '/templates/cerfa_11580_05.pdf', [
    'command' => "/usr/bin/pdftk"
    // or on most Windows systems:
    // 'command' => 'C:\Program Files (x86)\PDFtk\bin\pdftk.exe',
    //'useExec' => true,  // May help on Windows systems if execution fails
]);

$result = $pdf->generateFdfFile(__DIR__ . '/temporary_outputs/cerfa_11580_05.fdf');

if ($result === false) {
    $error = $pdf->getError();
    var_dump($error);
}

var_dump(getenv('PATH'));

var_dump($result);