<?php

namespace Associator;

use Associator\Exception\ClientException;

class Associator extends Client
{
    const ASSOCIATOR_BASE_URL = "api.associator.eu";
    const ASSOCIATOR_VERSION = "v1";

    const IMPORT_ITEM_SEPARATOR = ",";
    const IMPORT_TRANSACTION_SEPARATOR = "\n";


    /** @var string */
    private $apiKey;

    /** @var ClientInterface */
    private $client;

    /**
     * Associator constructor.
     * @param Client $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Create application in AssociatorAPI
     * @param $name
     * @param $provider
     * @return array
     */
    public function createApplication($name, $provider)
    {
        $url = sprintf('%s/%s/applications', self::ASSOCIATOR_BASE_URL, self::ASSOCIATOR_VERSION);
        try {
            $response = $this->client->request($url, Client::HTTP_POST, [
                'name' => $name,
                'provider' => $provider
            ]);
        } catch (ClientException $exception) {
            return ['status' => 'Error', 'message' => $exception->getMessage()];
        }

        return json_decode($response, true);
    }

    /**
     * Get associated items from AssociatorAPI
     * @param array $samples
     * @param float $support
     * @param float $confidence
     * @return array
     */
    public function getAssociations(array $samples, $support = null, $confidence = null)
    {
        if (!$this->getApiKey()) {
            return ['status' => 'Error', 'message' => 'Api key must be set.'];
        }

        $parameters['api_key'] = $this->getApiKey();
        $parameters['samples'] = json_encode($samples);

        if (isset($support)) {
            $parameters['support'] = $support;
        }

        if (isset($confidence)) {
            $parameters['confidence'] = $confidence;
        }

        $query = http_build_query($parameters);
        $url = sprintf('%s/%s/associations?%s', self::ASSOCIATOR_BASE_URL, self::ASSOCIATOR_VERSION, $query);
        try {
            $response = $this->client->request($url);
        } catch (ClientException $exception) {
            return ['status' => 'Error', 'message' => $exception->getMessage()];
        }

        return json_decode($response, true);
    }

    /**
     * Save single transaction items in AssociatorAPI
     * @param array $transactions
     * @return array
     */
    public function saveTransaction(array $transactions)
    {
        if (!$this->getApiKey()) {
            return ['status' => 'Error', 'message' => 'Api key must be set.'];
        }

        $url = sprintf('%s/%s/transactions', self::ASSOCIATOR_BASE_URL, self::ASSOCIATOR_VERSION);
        try {
            $response = $this->client->request($url, Client::HTTP_POST, [
                'api_key' => $this->getApiKey(),
                'items' => $transactions
            ]);
        } catch (ClientException $exception) {
            return ['status' => 'Error', 'message' => $exception->getMessage()];
        }

        return json_decode($response, true);
    }

    /**
     * Import transaction items to AssociatorAPI
     * @param array $data
     * @return array|mixed
     */
    public function importTransactions(array $data)
    {
        if (!$this->getApiKey()) {
            return ['status' => 'Error', 'message' => 'Api key must be set.'];
        }

        $items = array_map('implode', $data, array_fill(0, count($data), self::IMPORT_ITEM_SEPARATOR));
        $transactions = implode(self::IMPORT_TRANSACTION_SEPARATOR, $items);

        $url = sprintf('%s/%s/import', self::ASSOCIATOR_BASE_URL, self::ASSOCIATOR_VERSION);
        try {
            $response = $this->client->request($url, Client::HTTP_POST, [
                'api_key' => $this->apiKey,
                'data' => base64_encode($transactions)
            ]);
        } catch (ClientException $exception) {
            return ['status' => 'Error', 'message' => $exception->getMessage()];
        }

        return json_decode($response, true);
    }
}
