<?php
namespace Nreach\Base;
use \GuzzleHttp\Client;

class Service
{
    private $client = NULL;
    private $remoteHost = NULL;
    private $keys = NULL;
    private $delegate = NULL;

    private $mappings = [
        'imageanalyzer' => 'imageanalyzer',
        'textanalyzer' => 'textanalyzer',
        'chattoken' => 'bot'
    ];

    function __construct(string $remoteHost, array $keys, DataSourceDelegateInterface $delegate) {
        $this->client = new \GuzzleHttp\Client();
        $this->remoteHost = $remoteHost;
        $this->keys = $keys;
        $this->delegate = $delegate;
    }

    private function remote(string $method, array $arguments = [], $body = '') {
        $api = $this->mappings[$method];
        $url = $this->remoteHost . '' . $api;
        $arguments = [
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->keys[$api]
            ],
            'query' => $arguments,
            'json' => $body
        ];

        $result = $this->client->post($url, $arguments);
        return $result->getBody();
    }

    public function call(string $method, array $arguments = [], $body = '')
    {
        return $this->remote($method, $arguments, $body);
    }
}