<?php

namespace cerfaapp\controllers;

class Request
{
    public $parameters;
    public $reqMethod;
    public $contentType;

    public function __construct($parameters = [])
    {
        $this->parameters = $parameters;
        $this->reqMethod = trim($_SERVER['REQUEST_METHOD']);
        $this->contentType = !empty($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    }

    /**
     * @return string
     */
    public function getReqMethod(): string
    {
        return $this->reqMethod;
    }

/*    public function getBody()
    {
        if ($this->reqMethod !== 'POST') {
            return '';
        }

        $body = [];
        foreach ($_POST as $key => $value) {
            $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $body;
    }*/

    public function getJSON()
    {
        if ($this->reqMethod !== 'POST') {
            return [];
        }

        if (strcasecmp($this->contentType, 'application/json') !== 0) {
            return [];
        }

        // Receive the RAW post data.
        $postContent = trim(file_get_contents("php://input"));
        $decodedContent = json_decode($postContent, true);

        return $decodedContent;
    }
}