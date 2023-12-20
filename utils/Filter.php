<?php

namespace utils;


class Filter
{
    public static function pdfFile($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
    }
}