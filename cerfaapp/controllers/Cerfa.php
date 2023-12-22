<?php

namespace cerfaapp\controllers;

use cerfaapp\controllers\App_config;
use mikehaertl\pdftk\FdfFile;
use mikehaertl\pdftk\Pdf;
use utils\Filter;
use utils\Converter;


class Cerfa
{


    /**
     * Return FDF file from PDF templates.
     * @return array
     */
    public static function getTemplateFiles(): array
    {
        $fdfFiles = [];
        $directory = App_config::get('TEMPLATES_PATH');

        // treat only pdf files
        $templates = array_filter(scandir($directory), function($v, $k) {
            return Filter::isPdf($v);
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($templates as $template) {
            $pdf = new Pdf($directory . '/' . $template);

            $filePath = App_config::get('TEMPORARY_OUTPUTS_PATH') . '/' . pathinfo($template, PATHINFO_FILENAME) . '.fdf';
            $result = $pdf->generateFdfFile($filePath);


            if ($result === false) {
                $error = $pdf->getError();
                var_dump($error);
            } else {
                //$fdfFiles[$filePath] = file_get_contents($filePath);
                $fdfFiles[$filePath] = $filePath;
            }

        }

        return $fdfFiles;

    }


    /**
     * Return PDF file from JSON.
     * @param array $json
     * @param string $template
     * @return void
     */
    public static function getPDFFileFromJSON(array $json, string $template): void
    {
        print_r("getPDFFileFromJSON");
        $directory = App_config::get('TEMPLATES_PATH');
        $form = new Pdf($directory . '/' . $template);

        $filledForm = $form->fillForm($json)
            ->needAppearances()
            ->saveAs(App_config::get('TEMPORARY_OUTPUTS_PATH') . '/' . pathinfo($template, PATHINFO_FILENAME) . '.pdf');

        if ($filledForm === false) {
            $error = $form->getError();
            var_dump($error);
        }

    }


    public static function checkValidity(string $donorType, string $jsonData) {
        $fileName = App_config::get('CERFA_CODE_'.$donorType) . '.json';
        $path = App_config::get('MODELS_PATH');

        $model = json_decode(file_get_contents($path . '/' . $fileName), true);
        $data = json_decode($jsonData, true);

        $FDF = ["signature" => $data["signature"]];
        foreach($model as $field => $rules)
        {
            $value = @$data[$field];
            $mandatoryNotSatisfied = isset($rules['mandatory']) && $rules['mandatory'] === true && !$value;
            $dependencyNotSatisfied = isset($rules['dependency']) && (in_array($data[$rules['dependency']['field']], array_keys($rules['dependency']['values'])) && !$value);
            if ($mandatoryNotSatisfied || $dependencyNotSatisfied) return "missing field '$field'";

            if (isset($value)) {
                if ($rules['type'] === 'date' && !isValidDate($value)) {
                    return "incompatible date format for field '$field'";
                }
                if ($rules['type'] !== 'date' && gettype($value) !== $rules['type']) {
                    return "incompatible type for field '$field'";
                }
                if (isset($rules['dependency'])) {
                    $dependency = $rules['dependency']['field'];
                    if (isset($rules['dependency']['values'][$data[$dependency]]))
                        foreach ($rules['dependency']['values'][$data[$dependency]] as $subfield => $subvalue) {
                            if ($rules['type'] === 'date') {
                                $FDF[$subfield] = DateTime::createFromFormat('Y-m-d', $value)->format($subvalue);
                            } else {
                                $FDF[$subfield] = $subvalue;
                            }
                        }
                } else {
                    $FDF[$rules['field']] = $value;
                }
            }
        }
        return $FDF;
    }


    public static function generateReceipt(array $fdf, string $donorType): string
    {
        // each cerfa receipt are unique
        $id = uniqid();


        // create signature to file to be used as stamp
        $signature = Cerfa::createSignature($fdf['signature'], $id, $donorType);
        //var_dump($signature);
        //$signature = App_config::get('TEMPORARY_OUTPUTS_PATH') . '/signature.pdf';

        $fileName = App_config::get('CERFA_CODE_'.$donorType) . '.pdf';
        //var_dump($fileName);

        $form = new Pdf(App_config::get('TEMPLATES_PATH') . '/' . $fileName);
        $filledForm = $form->fillForm($fdf);

        $filledForm = new Pdf($filledForm);
        $signedForm = $filledForm
            ->flatten()
            ->multiStamp($signature)
            //->needAppearances()
            ->saveAs(App_config::get('TEMPORARY_OUTPUTS_PATH') . '/' . $id . '_' . $fileName);

        if ($signedForm === false) {
            throw new \Exception($filledForm->getError());
        }

        $signedForm = base64_encode( file_get_contents(App_config::get('TEMPORARY_OUTPUTS_PATH') . '/' . $id . '_' . $fileName) );
        unlink(App_config::get('TEMPORARY_OUTPUTS_PATH') . '/' . $id . '_' . $fileName);
        unlink($signature);

        return $signedForm;
    }






    /**
     * @return [left, top, width, height] of signature
     */
    public static function getSignaturePosition($donorType): array
    {
        $values = [
            "INDIVIDUAL" => [140, 217, 40, 16],
            "COMPANY" => [148, 221, 50, 20],
        ];

        return $values[$donorType];
    }

    /**
     * Create double page signature
     */
    private static function createSignature(string $base64Signature, $id, $donorType): string
    {
        $dirPath = App_config::get('TEMPORARY_OUTPUTS_PATH') . "/sign_$id";
        $png = $dirPath .'.png';
        $signatureStamp = $dirPath .'.pdf';
        //print_r('$png :' . PHP_EOL);
        //var_dump($png);
        file_put_contents($png, base64_decode($base64Signature));

        list($left, $top, $width, $height) = Cerfa::getSignaturePosition($donorType);

        $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $tcpdf->AddPage(); // page blank
        $tcpdf->AddPage();
        $tcpdf->Image($png, $left, $top, $width, $height, 'PNG'); // @TODO How to keep ratio ?
        $tcpdf->Output($signatureStamp, 'F');

        unlink($png);

        return $signatureStamp;
    }






}