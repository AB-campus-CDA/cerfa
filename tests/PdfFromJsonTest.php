<?php

use mikehaertl\pdftk\Pdf;
use cerfaapp\controllers\Cerfa;


class PdfFromJsonTest extends \PHPUnit\Framework\TestCase
{
/*    public function testPdfFromJsonCerfa11580()
    {
        $pdf = new Pdf('/app/templates/cerfa_11580_05.pdf');

        $json = json_decode(file_get_contents('/app/tests/inputs/textInputs.json'), true);

        // generate file with php-pdftk
        $result = $pdf->fillForm($json)
            ->needAppearances()
            ->saveAs('/app/tests/outputs/cerfa_11580_05_textInput.pdf');

        if ($result === false) {
            $error = $pdf->getError();
            var_dump($error);
        }

        $this->assertFileExists('/app/tests/outputs/cerfa_11580_05_textInput.pdf');

        // generate file with cerfaapp
        Cerfa::getPDFFileFromJSON($json, 'cerfa_11580_05.pdf');
        $this->assertFileExists('/app/temporary_outputs/cerfa_11580_05.pdf');

        // compare files
        $this->assertFileEquals('/app/tests/outputs/cerfa_11580_05_textInput.pdf', '/app/temporary_outputs/cerfa_11580_05.pdf');

    }
    public function testPdfFromJsonCerfa16216()
    {
        $pdf = new Pdf('/app/templates/cerfa_16216_01.pdf');

        $json = json_decode(file_get_contents('/app/tests/inputs/textInputs.json'), true);
        var_dump($json);

        // generate file with php-pdftk
        $result = $pdf->fillForm($json)
            ->needAppearances()
            ->saveAs('/app/tests/outputs/cerfa_16216_01_textInput.pdf');

        if ($result === false) {
            $error = $pdf->getError();
            var_dump($error);
        }

        $this->assertFileExists('/app/tests/outputs/cerfa_16216_01_textInput.pdf');

        // generate file with cerfaapp
        Cerfa::getPDFFileFromJSON($json, 'cerfa_16216_01.pdf');
        $this->assertFileExists('/app/temporary_outputs/cerfa_16216_01.pdf');

        // compare files
        $this->assertFileEquals('/app/tests/outputs/cerfa_16216_01_textInput.pdf', '/app/temporary_outputs/cerfa_16216_01.pdf');

    }*/
    public function testPdfFromJsonCAC1()
    {
        $pdf = new Pdf('/app/templates/cerfa_16216_01.pdf');

        $json = json_decode(file_get_contents('/app/tests/inputs/cac1.json'), true);

        // generate file with php-pdftk
        $result = $pdf->fillForm($json)
            ->needAppearances()
            ->saveAs('/app/tests/outputs/cerfa_16216_01_cac1.pdf');

        if ($result === false) {
            $error = $pdf->getError();
            var_dump($error);
        }

        $this->assertFileExists('/app/tests/outputs/cerfa_16216_01_cac1.pdf');

        // generate file with cerfaapp
        Cerfa::getPDFFileFromJSON($json, 'cerfa_16216_01.pdf');
        $this->assertFileExists('/app/temporary_outputs/cerfa_16216_01.pdf');

        // compare files
        $this->assertFileEquals('/app/tests/outputs/cerfa_16216_01_cac1.pdf', '/app/temporary_outputs/cerfa_16216_01.pdf');

    }
    public function testPdfFromJsonCAC2()
    {
        $pdf = new Pdf('/app/templates/cerfa_16216_01.pdf');

        $json = json_decode(file_get_contents('/app/tests/inputs/cac2.json'), true);

        // generate file with php-pdftk
        $result = $pdf->fillForm($json)
            ->needAppearances()
            ->saveAs('/app/tests/outputs/cerfa_16216_01_cac2.pdf');

        if ($result === false) {
            $error = $pdf->getError();
            var_dump($error);
        }

        $this->assertFileExists('/app/tests/outputs/cerfa_16216_01_cac2.pdf');

        // generate file with cerfaapp
        Cerfa::getPDFFileFromJSON($json, 'cerfa_16216_01.pdf');
        $this->assertFileExists('/app/temporary_outputs/cerfa_16216_01.pdf');

        // compare files
        $this->assertFileEquals('/app/tests/outputs/cerfa_16216_01_cac2.pdf', '/app/temporary_outputs/cerfa_16216_01.pdf');

    }
    public function testPdfFromJsonCACall()
    {
        for ($i = 0; $i <= 50; $i++) {

            // CAC32 to CAC39 are not used
            if ($i == 32) {
                $i = 40;
            }

            $json = ["CAC$i" => "1"];

            $pdf = new Pdf('/app/templates/cerfa_16216_01.pdf');

            // generate file with php-pdftk
            $result = $pdf->fillForm($json)
                ->needAppearances()
                ->saveAs("/app/tests/outputs/cerfa_16216_01_cac$i.pdf");

            if ($result === false) {
                $error = $pdf->getError();
                var_dump($error);
            }

            $this->assertFileExists("/app/tests/outputs/cerfa_16216_01_cac$i.pdf");

            // generate file with cerfaapp
            Cerfa::getPDFFileFromJSON($json, 'cerfa_16216_01.pdf');
            $this->assertFileExists('/app/temporary_outputs/cerfa_16216_01.pdf');

            // compare files
            $this->assertFileEquals('/app/temporary_outputs/cerfa_16216_01.pdf',"/app/tests/outputs/cerfa_16216_01_cac$i.pdf");

            // delete temporary test file
            unlink("/app/tests/outputs/cerfa_16216_01_cac$i.pdf");
        }

    }

}