<?php

namespace cerfaapp\controllers;

class RequestValidity
{

    private string $dateRegex = '/^([0-9]{4})-([0-1]{1})([0-9]{1})-([0-3]{1})([0-9]{1})$/';
    private array $allowedDonorTypes = ['INDIVIDUAL', 'COMPANY'];
    private array $allowedDonationTypes = ['MONEY', 'INKIND'];
    private array $allowedOrganismStatusTypesForIndividual = [
        '1901_LAW',
        'PUBLIC_UTILITY',
        'OTHER',
        'UNIVERSITY',
        'COMPANY_FONDATION',
        'MUSEUM',
        'FOREST_MANAGE',
        'NGO',
        'ALSACE_MOSELLE',
        'DOTATION',
        'PRESS_PLURALISM',
        'ART_EDUCATION',
        'CONSULAR_EDUCATION',
        'SMB_FINANCIAL_SUPPORT',
        'ART_PRESENTATION',
        'HISTORICAL_MONUMENT',
        'CULTURAL_PROTECTION',
        'PRIVATE_RESEARCH',
        'COMPANY_SOCIAL',
        'INTERMEDIARY_ASSOCIATION',
        'WORKSHOP_SOCIAL',
        'ADAPTED_COMPANY',
        'ANR',
        'EMPLOYERS_GROUP',
        'RECOVERY_COMPANY',
        'EU_ORGANISM'
    ];
    private array $allowedOrganismStatusTypesForCompany = [
        '1901_LAW',
        'PUBLIC_UTILITY',
        'OTHER',
        'UNIVERSITY',
        'COMPANY_FONDATION',
        'MUSEUM',
        //'FOREST_MANAGE', // don't ask me why
        'NGO',
        'ALSACE_MOSELLE',
        'DOTATION',
        'PRESS_PLURALISM',
        'ART_EDUCATION',
        'CONSULAR_EDUCATION',
        'SMB_FINANCIAL_SUPPORT',
        'ART_PRESENTATION',
        'HISTORICAL_MONUMENT',
        'CULTURAL_PROTECTION',
        'PRIVATE_RESEARCH',
        'COMPANY_SOCIAL',
        'INTERMEDIARY_ASSOCIATION',
        'WORKSHOP_SOCIAL',
        'ADAPTED_COMPANY',
        'ANR',
        'EMPLOYERS_GROUP',
        'RECOVERY_COMPANY',
        'EU_ORGANISM'
    ];
    private array $allowedDonationForm = ['AUTHENTIC', 'PRIVATE', 'MANUAL', 'OTHER'];
    private array $allowedDonationNature = ['MONETARY', 'LISTED', 'ABANDONMENT', 'VOLUNTEERS', 'OTHER'];
    private array $allowedMethodOfPaymentForIndividual = ['CASH', 'BANK_CHECK', 'TRANSFER'];
    private array $allowedMethodOfPaymentForCompany = ['CASH', 'BANK_CHECK', 'TRANSFER', 'OTHER'];

    /**
     * Validator will return if data are valid or not.
     * @param Request $request
     * @return bool
     */
    public function __invoke(Request $request): bool
    {
        $data = $request->getJSON();

        if (!$this->checkMinimumRequirement($data)) {
            return false;
        } else {
            return $this->checkOrganismOptionalFields($data) && ($this->checkIndividualDonor($data) || $this->checkCompanyDonor($data));
        }
    }


    private function checkMinimumRequirement($data):bool {
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
            && isset($data['donor']['type']) && in_array($data['donor']['type'], $this->allowedDonorTypes)
            && isset($data['donor']['address']['number']) && gettype($data['donor']['address']['number'] ) === 'string'
            && isset($data['donor']['address']['street']) && gettype($data['donor']['address']['street'] ) === 'string'
            && isset($data['donor']['address']['city']) && gettype($data['donor']['address']['city'] ) === 'string'
            && isset($data['donor']['address']['postCode'])) && gettype($data['donor']['address']['postCode'] ) === 'string'
            && isset($data['donations'][0]['type']) && in_array($data['donations'][0]['type'], $this->allowedDonationTypes)
            && isset($data['donations'][0]['amount']) && is_numeric($data['donations'][0]['amount']) && ($data['donations'][0]['amount']>0);
    }

    private function checkOrganismOptionalFields($data):bool {
        return isset($data['organism']['status']['optionalFields']['date1']) && gettype($data['organism']['status']['optionalFields']['date1'] ) === 'string'&& preg_match($this->dateRegex, $data['date'])
            && isset($data['organism']['status']['optionalFields']['date2']) && gettype($data['organism']['status']['optionalFields']['date2'] ) === 'string'&& preg_match($this->dateRegex, $data['date'])
            && isset($data['organism']['status']['optionalFields']['reason']) && gettype($data['organism']['status']['optionalFields']['reason'] ) === 'string';
    }

    private function checkIndividualDonor($data):bool {
        return (
            //organism data
            isset($data['organism']['status']['type']) && in_array($data['organism']['status']['type'], $this->allowedOrganismStatusTypesForIndividual)
            // donor data
            && isset($data['donor']['firstName']) && gettype($data['donor']['firstName'] ) === 'string'
            && isset($data['donor']['lastName']) && gettype($data['donor']['lastName'] ) === 'string'
            && isset($data['donor']['address']['number']) && gettype($data['donor']['address']['number'] ) === 'string'
            && isset($data['donor']['address']['street']) && gettype($data['donor']['address']['street'] ) === 'string'
            && isset($data['donor']['address']['city']) && gettype($data['donor']['address']['city'] ) === 'string'
            && isset($data['donor']['address']['postCode']) && gettype($data['donor']['address']['postCode'] ) === 'string'
            && isset($data['donor']['address']['country']) && gettype($data['donor']['address']['country'] ) === 'string'
            // donations data
            && isset($data['donations'][0]['type']) && in_array($data['donations'][0]['type'], $this->allowedDonationTypes)
            && isset($data['donations'][0]['optionalFields']['donationForm']) && in_array($data['donations'][0]['optionalFields']['donationForm'], $this->allowedDonationForm)
            && isset($data['donations'][0]['optionalFields']['nature']) && in_array($data['donations'][0]['optionalFields']['nature'], $this->allowedDonationNature)
            && (!($data['donations'][0]['optionalFields']['nature'] === 'OTHER') || gettype($data['donations'][0]['optionalFields']['otherNature']) === 'string')
            && (!($data['donations'][0]['optionalFields']['nature'] === 'MONETARY') || in_array($data['donations'][0]['optionalFields']['methodOfPayment'], $this->allowedMethodOfPaymentForIndividual))
        );
    }

    private function checkCompanyDonor($data):bool {
        return (
            isset($data['donor']['name']) && gettype($data['donor']['name'] ) === 'string'
        );
    }

}