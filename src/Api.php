<?php

namespace Sukvojte\Coinbase;

use \GuzzleHttp\Client;
use Firebase\JWT\JWT;

class Api
{

    private string $client_id;

    private string $client_key;

    private string $base_host = 'api.coinbase.com';

    private string $base_path = '/api/v3/';

    private string $api_protocol = 'https';

    private Client $client;

    public function __construct($client_id, $client_key, $use_sandbox = false)
    {
        $this->client_id = $client_id;
        $this->client_key = $client_key;
        $this->client = new Client(['base_uri' => $this->getBaseUrl()]);
    }

    protected function createToken($uri, $service = 'retail_rest_api_proxy')
    {
        $jwt_payload = [
            'sub' => $this->client_id,
            'iss' => "coinbase-cloud",
            'nbf' => time() - 5,
            'exp' => time() + 60,
            'aud' => [$service],
            'uri' => $uri,
        ];

        return JWT::encode($jwt_payload, $this->client_key, 'ES256',  $this->client_id, ['nonce' => '' . time()]);
    }

    public function get($path, $parameters = [])
    {
        $token = $this->createToken($this->getPath('GET', $path));

        $response = $this->client->get($path, [
            'query' => $parameters,
            'headers' =>
            [
                'Authorization' => "Bearer {$token}"
            ]
        ]);

        return json_decode($response->getBody());
    }

    private function getBaseUrl()
    {
        return $this->api_protocol . '://' . $this->base_host . $this->base_path;
    }

    private function getPath($proto = false, $path = '')
    {
        return $proto . (!empty($proto) ? ' ' : '') . $this->base_host . $this->base_path . $path;
    }
}
