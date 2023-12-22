<?php

namespace cerfaapp\controllers;

class Response
{
    private $status = 200;

    public function setStatus(int $code)
    {
        $this->status = $code;
    }

    public function toJSON($data = [])
    {
        http_response_code($this->status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
        //echo $data;
    }
}