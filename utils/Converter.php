<?php

namespace utils;

class Converter
{
    public static function jsonToArray($json): array
    {
        $output = [];
        foreach ($json as $key => $value) {
            $output[$key] = $value;
        }
        return $output;
    }
}