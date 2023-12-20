<?php

class ReqJsonTest extends \PHPUnit\Framework\TestCase
{
    public function ReqJsonIsOk($json)
    {
        $this->assertJson($json);

        $data = json_decode($json, true);

        $this->assertIsInt($data['receitNumber']);
        $this->assertIsString($data['organism']['name']);
        $this->assertIsString($data['organism']['sirenOrRna']);
        $this->assertIsString($data['organism']['address']['number']);
        $this->assertIsString($data['organism']['address']['street']);
        $this->assertIsString($data['organism']['address']['city']);
        $this->assertIsString($data['organism']['address']['postCode']);
        $this->assertIsString($data['organism']['address']['country']);
        $this->assertIsString($data['organism']['object']);
        $this->assertIsString($data['organism']['status']['type']);
        if ($data['organism']['status']['optionalFields']['date1']) {
            $this->assertIsString($data['organism']['status']['optionalFields']['date1']);
        }
        if ($data['organism']['status']['optionalFields']['date2']) {
            $this->assertIsString($data['organism']['status']['optionalFields']['date2']);
        }
        if ($data['organism']['status']['optionalFields']['reason']) {
            $this->assertIsString($data['organism']['status']['optionalFields']['reason']);
        }
        $this->assertIsString($data['organism']['status']['date']);
        $this->assertIsString($data['donor']['type']);
        if ($data['donor']['name']) {
            $this->assertIsString($data['donor']['name']);
        }
        if ($data['donor']['firstName']) {
            $this->assertIsString($data['donor']['firstName']);
        }
        if ($data['donor']['lastName']) {
            $this->assertIsString($data['donor']['lastName']);
        }
        if ($data['donor']['legalForm']) {
            $this->assertIsString($data['donor']['legalForm']);
        }
        if ($data['donor']['siren']) {
            $this->assertIsString($data['donor']['siren']);
        }
        $this->assertIsString($data['donor']['address']['number']);
        $this->assertIsString($data['donor']['address']['street']);
        $this->assertIsString($data['donor']['address']['city']);
        $this->assertIsString($data['donor']['address']['postCode']);
        if ($data['donor']['address']['country']) {
            $this->assertIsString($data['donor']['address']['country']);
        }
        $this->assertIsString($data['donation']['0']['type']);
        if ($data['donation']['0']['amount']) {
            $this->assertIsFloat($data['donation']['0']['amount']);
        }
        $this->assertIsString($data['donation']['0']['date']);
        if ($data['donation']['0']['optionalField']['donationForm']) {
            $this->assertIsInt($data['donation']['0']['optionalField']['donationForm']);
        }
        if ($data['donation']['0']['optionalField']['nature']) {
            $this->assertIsString($data['donation']['0']['optionalField']['nature']);
        }
        if ($data['donation']['0']['optionalField']['methodOfPayment']) {
            $this->assertIsString($data['donation']['0']['optionalField']['methodOfPayment']);
        }
        if ($data['donation']['0']['optionalField']['reason']) {
            $this->assertIsString($data['donation']['0']['optionalField']['reason']);
        }
        if ($data['donation']['0']['optionalField']['cgi']['200']) {
            $this->assertIsBool($data['donation']['0']['optionalField']['cgi']['200']);
        }
        if ($data['donation']['0']['optionalField']['cgi']['978']) {
            $this->assertIsBool($data['donation']['0']['optionalField']['cgi']['978']);
        }

    }
}