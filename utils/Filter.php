<?php

namespace utils;


class Filter
{
    public static function isPdf($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
    }
}