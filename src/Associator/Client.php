<?php

namespace Associator;

class Client
{
    /**
     * @param $url
     * @param string $method
     * @param array $data
     * @return mixed
     * @throws ClientException
     */
    public function request($url, $method = 'GET', $data = [])
    {
        $curl = curl_init();

        $settings[CURLOPT_URL] = $url;
        $settings[CURLOPT_RETURNTRANSFER] = 1;
        $settings[CURLOPT_HTTPHEADER] = ['Content-Type:application/json'];

        if ($method === 'POST') {
            $settings[CURLOPT_CUSTOMREQUEST] = "POST";
            $settings[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $settings);
        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) {
            throw new ClientException();
        }

        return $response;
    }
}
