<?php

namespace cerfaapp\controllers;

class Router
{
    public static function get($appRoute, $appCallback)
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
            return;
        }

        self::on($appRoute, $appCallback);
    }

    public static function post($appRoute, $appCallback)
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
            return;
        }

        self::on($appRoute, $appCallback);
    }

    public static function on($route, $callback)
    {
        $parameters = $_SERVER['REQUEST_URI'];
        $parameters = (stripos($parameters, "/") !== 0) ? "/" . $parameters : $parameters;
        //var_dump($parameters);
        $route = str_replace('/', '\/', $route);
        $matched = preg_match('/^' . ($route) . '$/', $parameters, $isMatched, PREG_OFFSET_CAPTURE);

        if ($matched) {
            // first value is normally the route, lets remove it
            array_shift($isMatched);
            // Get the matches as parameters
            $parameters = array_map(function ($parameter) {
                return $parameter[0];
            }, $isMatched);
            $callback(new Request($parameters), new Response());
        }
    }
}