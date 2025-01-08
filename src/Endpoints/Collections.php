<?php

namespace AlvinCoded\MtnMomoAi\Endpoints;

use AlvinCoded\MtnMomoAi\Traits\MakesHttpRequests;

class Collections
{
    use MakesHttpRequests;

    protected $config;
    protected $baseUrl;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = $config['base_url'] . '/collection/' . $config['version'];
    }

    public function requestToPay($externalId, $partyId, $amount, $currency, $payerMessage, $payeeNote)
    {
        $endpoint = $this->baseUrl . '/requesttopay';
        $body = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $externalId,
            'payer' => [
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
        $endpoint = $this->baseUrl . '/requesttopay/' . $referenceId;
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

    public function requestToPayDeliveryNotification($referenceId, $notificationMessage)
    {
        $endpoint = $this->baseUrl . '/requesttopay/' . $referenceId . '/deliverynotification';
        $body = [
            'notificationMessage' => $notificationMessage
        ];
        return $this->makeRequest('POST', $endpoint, $body);
    }

    public function requestToPayTransaction($amount, $currency, $externalId, $partyId, $payerMessage, $payeeNote)
    {
        $endpoint = $this->baseUrl . '/requesttopay';
        $body = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $externalId,
            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $partyId
            ],
            'payerMessage' => $payerMessage,
            'payeeNote' => $payeeNote
        ];
        return $this->makeRequest('POST', $endpoint, $body);
    }

    public function requestToWithdraw($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote)
    {
        $endpoint = $this->baseUrl . '/requesttowithdraw';
        $body = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $externalId,
            'payee' => $payee,
            'payerMessage' => $payerMessage,
            'payeeNote' => $payeeNote
        ];
        return $this->makeRequest('POST', $endpoint, $body);
    }

    public function getWithdrawalTransactionStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/requesttowithdraw/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }

    public function getBCAuthorization($accountHolderMSISDN, $accountHolderUUID)
    {
        $endpoint = $this->baseUrl . '/bc-authorize';
        $body = [
            'accountHolderMSISDN' => $accountHolderMSISDN,
            'accountHolderUUID' => $accountHolderUUID
        ];
        return $this->makeRequest('POST', $endpoint, $body);
    }
}
