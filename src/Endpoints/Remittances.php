<?php

namespace AlvinCoded\MtnMomoAi\Endpoints;

use AlvinCoded\MtnMomoAi\Traits\MakesHttpRequests;

/**
 * Class Remittances
 * 
 * Handles all remittance-related operations for the MTN MOMO API.
 * This class provides methods for transferring money, checking balances,
 * and managing remittance transactions.
 *
 * @package AlvinCoded\MtnMomoAi\Endpoints
 */
class Remittances
{
    use MakesHttpRequests;

    /** @var array Configuration settings for the remittance endpoint */
    protected $config;

    /** @var string Base URL for all remittance API endpoints */
    protected $baseUrl;

    /**
     * Initialize a new Remittances instance.
     *
     * @param array $config Configuration array containing base_url and version
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = $config['base_url'] . '/remittance/' . $config['version'];
    }

    /**
     * Transfer money to a payee using remittance.
     *
     * @param string $externalId    Unique identifier for the transaction
     * @param string $partyId       The phone number of the payee
     * @param float  $amount        Amount to transfer
     * @param string $currency      Currency of the transfer (e.g., EUR, USD)
     * @param string $payerMessage  Message from the payer
     * @param string $payeeNote     Note to the payee
     * 
     * @return array Response from the API
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
     * Get the status of a specific remittance transaction.
     *
     * @param string $referenceId The reference ID of the transaction
     * 
     * @return array Transaction status details
     */
    public function getTransactionStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/transfer/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get the current balance of the remittance account.
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
     * @param string $accountHolderId     ID of the account holder
     * @param string $accountHolderIdType Type of the ID (e.g., MSISDN)
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
     * @param string $accountHolderId     ID of the account holder
     * @param string $accountHolderIdType Type of the ID (e.g., MSISDN)
     * 
     * @return array Account holder status information
     */
    public function validateAccountHolderStatus($accountHolderId, $accountHolderIdType)
    {
        $endpoint = $this->baseUrl . '/accountholder/' . $accountHolderIdType . '/' . $accountHolderId . '/active';
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get basic information about a user using their MSISDN.
     *
     * @param string $msisdn Mobile number of the user
     * 
     * @return array Basic user information
     */
    public function getBasicUserinfo($msisdn)
    {
        $endpoint = $this->baseUrl . '/accountholder/msisdn/' . $msisdn . '/basicuserinfo';
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Initiate a cash transfer transaction.
     *
     * @param string $externalId    Unique identifier for the transaction
     * @param float  $amount        Amount to transfer
     * @param string $currency      Currency of the transfer
     * @param string $payerMessage  Message from the payer
     * @param string $payeeNote     Note to the payee
     * 
     * @return array Response from the API
     */
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

    /**
     * Get the status of a specific cash transfer transaction.
     *
     * @param string $referenceId The reference ID of the cash transfer
     * 
     * @return array Cash transfer status details
     */
    public function getCashTransferStatus($referenceId)
    {
        $endpoint = $this->baseUrl . '/cashtransfer/' . $referenceId;
        return $this->makeRequest('GET', $endpoint);
    }
}