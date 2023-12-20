<?php

namespace cerfaapp\controllers;

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

}