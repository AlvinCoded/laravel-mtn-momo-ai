<?php

namespace AlvinCoded\MtnMomoAi\Endpoints;

use AlvinCoded\MtnMomoAi\Traits\MakesHttpRequests;

/**
 * Class Disbursements
 * 
 * Handles all disbursement-related operations for the MTN MOMO API.
 * This class provides methods for transferring money, checking transaction status,
 * and managing account information.
 *
 * @package AlvinCoded\MtnMomoAi\Endpoints
 */
class Disbursements
{
    use MakesHttpRequests;

    /** @var array Configuration settings for the disbursement endpoint */
    protected $config;

    /** @var string Base URL for all disbursement API requests */
    protected $baseUrl;

    /**
     * Initialize a new Disbursements instance.
     *
     * @param array $config Configuration array containing base_url and version
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = $config['base_url'] . '/disbursement/' . $config['version'];
    }

    /**
     * Transfer money to a payee.
     *
     * @param string $externalId    Unique identifier for the transaction
     * @param string $partyId       The phone number of the payee
     * @param float  $amount        Amount to transfer
     * @param string $currency      Currency of transfer (e.g., EUR, USD)
     * @param string $payerMessage  Message for the payer's statement
     * @param string $payeeNote     Note for the payee
     * 
     * @return array Response from the API
     * @throws \AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException
     */
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

    /**
     * Get the status of a specific transfer transaction.
     *
     * @param string $referenceId The reference ID of the transfer
     * 
     * @return array Transaction status details
     * @throws \AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException
     */
    public function getTransactionStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/transfer/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get the account balance for the disbursement account.
     *
     * @return array Account balance information
     * @throws \AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException
     */
    public function getAccountBalance()
    {
        $endpoint = $this->baseUrl . '/account/balance';
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get information about an account holder.
     *
     * @param string $accountHolderId     The ID of the account holder
     * @param string $accountHolderIdType The type of the account holder ID (e.g., MSISDN)
     * 
     * @return array Account holder information
     * @throws \AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException
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
     * @param string $accountHolderIdType The type of the account holder ID
     * 
     * @return array Account holder status
     * @throws \AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException
     */
    public function validateAccountHolderStatus($accountHolderId, $accountHolderIdType)
    {
        $endpoint = $this->baseUrl . '/accountholder/' . $accountHolderIdType . '/' . $accountHolderId . '/active';
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get basic information about a user by their MSISDN.
     *
     * @param string $msisdn The phone number of the user
     * 
     * @return array User information
     * @throws \AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException
     */
    public function getBasicUserinfo($msisdn)
    {
        $endpoint = $this->baseUrl . '/accountholder/msisdn/' . $msisdn . '/basicuserinfo';
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Initiate a refund for a previous transfer.
     *
     * @param string $externalId    Unique identifier for the refund
     * @param float  $amount        Amount to refund
     * @param string $currency      Currency of the refund
     * @param string $referenceId   Reference ID of the original transfer
     * @param string $payerMessage  Message for the payer's statement
     * @param string $payeeNote     Note for the payee
     * 
     * @return array Refund response
     * @throws \AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException
     */
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

    /**
     * Get the status of a refund transaction.
     *
     * @param string $referenceId The reference ID of the refund
     * 
     * @return array Refund status details
     * @throws \AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException
     */
    public function getRefundStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/refund/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get the status of a deposit transaction.
     *
     * @param string $referenceId The reference ID of the deposit
     * 
     * @return array Deposit status details
     * @throws \AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException
     */
    public function getDepositStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/deposit/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }
}