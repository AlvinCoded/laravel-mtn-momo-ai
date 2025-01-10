<?php

namespace AlvinCoded\MtnMomoAi\Endpoints;

use AlvinCoded\MtnMomoAi\Traits\MakesHttpRequests;

/**
 * Class Collections
 * 
 * Handles all Collection-related operations for the MTN MOMO API.
 * This class provides methods to initiate payments, check transaction status,
 * and manage account information.
 *
 * @package AlvinCoded\MtnMomoAi\Endpoints
 */
class Collections
{
    use MakesHttpRequests;

    /** @var array Configuration settings for the Collections API */
    protected $config;

    /** @var string Base URL for all Collection API endpoints */
    protected $baseUrl;

    /**
     * Initialize a new Collections instance.
     *
     * @param array $config Configuration array containing base_url and version
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = $config['base_url'] . '/collection/' . $config['version'];
    }

    /**
     * Request a payment from a customer.
     *
     * @param string $externalId     Unique identifier for the transaction
     * @param string $partyId        The phone number of the customer (MSISDN)
     * @param float  $amount         Amount to be paid
     * @param string $currency       Currency of the amount (e.g., EUR, USD)
     * @param string $payerMessage   Message to be displayed to the payer
     * @param string $payeeNote      Note for the payee's records
     * 
     * @return array Response from the API containing transaction details
     */
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

    /**
     * Get the status of a specific transaction.
     *
     * @param string $referenceId The reference ID of the transaction
     * 
     * @return array Transaction status details
     */
    public function getTransactionStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/requesttopay/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get the balance of the account.
     *
     * @return array Account balance information
     */
    public function getAccountBalance()
    {
        $endpoint = $this->baseUrl . '/account/balance';
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get information about a specific account holder.
     *
     * @param string $accountHolderId     The ID of the account holder
     * @param string $accountHolderIdType The type of the ID (e.g., MSISDN)
     * 
     * @return array Account holder information
     */
    public function getAccountHolder($accountHolderId, $accountHolderIdType)
    {
        $endpoint = $this->baseUrl . '/accountholder/' . $accountHolderIdType . '/' . $accountHolderId . '/active';
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Validate the status of an account holder.
     *
     * @param string $accountHolderId     The ID of the account holder
     * @param string $accountHolderIdType The type of the ID (e.g., MSISDN)
     * 
     * @return array Account holder status information
     */
    public function validateAccountHolderStatus($accountHolderId, $accountHolderIdType)
    {
        $endpoint = $this->baseUrl . '/accountholder/' . $accountHolderIdType . '/' . $accountHolderId . '/active';
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Send a delivery notification for a request to pay transaction.
     *
     * @param string $referenceId         The reference ID of the transaction
     * @param string $notificationMessage The message to be sent in the notification
     * 
     * @return array Notification delivery status
     */
    public function requestToPayDeliveryNotification($referenceId, $notificationMessage)
    {
        $endpoint = $this->baseUrl . '/requesttopay/' . $referenceId . '/deliverynotification';
        $body = [
            'notificationMessage' => $notificationMessage
        ];
        return $this->makeRequest('POST', $endpoint, $body);
    }

    /**
     * Initiate a withdrawal request.
     *
     * @param float  $amount       Amount to withdraw
     * @param string $currency     Currency of the amount
     * @param string $externalId   External transaction ID
     * @param array  $payee        Payee information
     * @param string $payerMessage Message for the payer
     * @param string $payeeNote    Note for the payee
     * 
     * @return array Withdrawal request response
     */
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

    /**
     * Get the status of a withdrawal transaction.
     *
     * @param string $referenceId The reference ID of the withdrawal
     * 
     * @return array Withdrawal transaction status
     */
    public function getWithdrawalTransactionStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/requesttowithdraw/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get BC authorization for an account holder.
     *
     * @param string $accountHolderMSISDN The MSISDN of the account holder
     * @param string $accountHolderUUID   The UUID of the account holder
     * 
     * @return array BC authorization response
     */
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
