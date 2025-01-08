<?php

namespace AlvinCoded\MtnMomoAi\Endpoints;

use AlvinCoded\MtnMomoAi\Traits\MakesHttpRequests;

class Disbursements
{
    use MakesHttpRequests;

    protected $config;
    protected $baseUrl;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = $config['base_url'] . '/disbursement/' . $config['version'];
    }

    public function transfer($externalId, $partyId, $amount, $currency, $payerMessage, $payeeNote)
    {
        $endpoint = $this->baseUrl . '/transfer';
        $body = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $externalId,
            'payee' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $partyId
            ],
            'payerMessage' => $payerMessage,
            'payeeNote' => $payeeNote
        ];
        return $this->makeRequest('POST', $endpoint, $body);
    }

    public function getTransactionStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/transfer/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }

    public function getAccountBalance()
    {
        $endpoint = $this->baseUrl . '/account/balance';
        return $this->makeRequest('GET', $endpoint);
    }

    public function getAccountHolder($accountHolderId, $accountHolderIdType)
    {
        $endpoint = $this->baseUrl . '/accountholder/' . $accountHolderIdType . '/' . $accountHolderId . '/active';
        return $this->makeRequest('GET', $endpoint);
    }

    public function validateAccountHolderStatus($accountHolderId, $accountHolderIdType)
    {
        $endpoint = $this->baseUrl . '/accountholder/' . $accountHolderIdType . '/' . $accountHolderId . '/active';
        return $this->makeRequest('GET', $endpoint);
    }

    public function getBasicUserinfo($msisdn)
    {
        $endpoint = $this->baseUrl . '/accountholder/msisdn/' . $msisdn . '/basicuserinfo';
        return $this->makeRequest('GET', $endpoint);
    }

    public function refund($externalId, $amount, $currency, $referenceId, $payerMessage, $payeeNote)
    {
        $endpoint = $this->baseUrl . '/refund';
        $body = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $externalId,
            'referenceId' => $referenceId,
            'payerMessage' => $payerMessage,
            'payeeNote' => $payeeNote
        ];
        return $this->makeRequest('POST', $endpoint, $body);
    }

    public function getRefundStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/refund/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }

    public function getDepositStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/deposit/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }
}
