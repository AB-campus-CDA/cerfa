<?php

namespace cerfaapp\controllers;

class RequestValidity
{
    /**
     * Validator will return if data are valid or not.
     * @param Request $request
     * @return bool
     */
    public function __invoke(Request $request): bool
    {
        $data = $request->getJSON();

        // check minimum required fields
        return (
            isset($data['receiptNumber']) && gettype($data['receiptNumber'] ) === 'integer'
            && isset($data['organism']['name']) && gettype($data['organism']['name'] ) === 'string'
            && isset($data['organism']['sirenOrRna']) && gettype($data['organism']['sirenOrRna'] ) === 'string'
            && isset($data['organism']['address']['number']) && gettype($data['organism']['address']['number'] ) === 'string'
            && isset($data['organism']['address']['street']) && gettype($data['organism']['address']['street'] ) === 'string'
            && isset($data['organism']['address']['city']) && gettype($data['organism']['address']['city'] ) === 'string'
            && isset($data['organism']['address']['postCode']) && gettype($data['organism']['address']['postCode'] ) === 'string'
            && isset($data['organism']['address']['country']) && gettype($data['organism']['address']['country'] ) === 'string'
            && isset($data['organism']['object']) && gettype($data['organism']['object'] ) === 'string'
            && isset($data['organism']['status']['type']) && gettype($data['organism']['status']['type'] ) === 'string'
            && isset($data['donor']['type']) && gettype($data['donor']['type'] ) === 'string'
            && isset($data['donor']['address']['number']) && gettype($data['donor']['address']['number'] ) === 'string'
            && isset($data['donor']['address']['street']) && gettype($data['donor']['address']['street'] ) === 'string'
            && isset($data['donor']['address']['city']) && gettype($data['donor']['address']['city'] ) === 'string'
            && isset($data['donor']['address']['postCode'])) && gettype($data['donor']['address']['postCode'] ) === 'string'
            && isset($data['donation'][0]['type']) && gettype($data['donation'][0]['type'] ) === 'string'
            && isset($data['donation'][0]['amount']) && gettype($data['donation'][0]['amount'] ) === 'double';

    }
}