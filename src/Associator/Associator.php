<?php

namespace Associator;

class Associator extends Client
{
    const BASE_URL = 'api.associator.eu';
    const VERSION = 'v1';

    /** @var string */
    private $apiKey;

    /** @var Client */
    private $client;

    /**
     * Associator constructor.
     * @param Client $client
     * @param $apiKey
     */
    public function __construct(Client $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * Save single transaction items
     * @param array $transactions
     * @return bool
     */
    public function saveTransaction(array $transactions)
    {
        $url = sprintf('%s/%s/transactions', self::BASE_URL, self::VERSION);
        try {
            $data = $this->client->request($url, 'POST', [
                'api_key' => $this->apiKey,
                'transaction' => $transactions
            ]);
        } catch (ClientException $exception) {
            return false;
        }
        $response = json_decode($data, true);

        return $response['status'] === 'Success';
    }

    /**
     * Get associated items
     * @param array $samples
     * @param null $support Default value is defined in documentation
     * @param null $confidence Default value is defined in documentation
     * @return array
     */
    public function getAssociations(array $samples, $support = null, $confidence = null)
    {
        $parameters['api_key'] = $this->apiKey;
        $parameters['samples'] = json_encode($samples);

        if (isset($support)) {
            $parameters['support'] = $support;
        }

        if (isset($confidence)) {
            $parameters['confidence'] = $confidence;
        }

        $query = http_build_query($parameters);
        $url = sprintf('%s/%s/associations?%s', self::BASE_URL, self::VERSION, $query);
        try {
            $data = $this->client->request($url);
        } catch (ClientException $exception) {
            return [];
        }

        $response = json_decode($data, true);

        return isset($response['associations']) ? $response['associations'] : [];
    }
}
