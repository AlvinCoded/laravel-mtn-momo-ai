<?php

namespace AlvinCoded\MtnMomoAi\Endpoints;

use AlvinCoded\MtnMomoAi\Traits\MakesHttpRequests;

class Remittances
{
    use MakesHttpRequests;

    protected $config;
    protected $baseUrl;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = $config['base_url'] . '/remittance/' . $config['version'];
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

    public function cashTransfer($externalId, $amount, $currency, $payerMessage, $payeeNote)
    {
        $endpoint = $this->baseUrl . '/cashtransfer';
        $body = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $externalId,
            'payerMessage' => $payerMessage,
            'payeeNote' => $payeeNote
        ];
        return $this->makeRequest('POST', $endpoint, $body);
    }

    public function getCashTransferStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/cashtransfer/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }
}
