<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;

class EventsSearchService
{
    private $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
        $this->configuration['url'] = rtrim($this->configuration['url'], '/').'/';
        $url = parse_url($this->configuration['url']);
        $this->configuration['base_url'] = $url['scheme'].'://'.$url['host'];
    }

    public function search($query)
    {
        $client = new Client();
        $response = $client->request('GET', 'events', [
            'base_uri' => $this->configuration['url'],
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => [
                'name' => $query,
            ],
        ]);

        $results = [];

        if ($response->getStatusCode() === 200) {
            $results = json_decode((string) $response->getBody(), true);
            foreach ($results as &$result) {
                $result['id'] = $this->configuration['base_url'].$result['@id'].'.json';
            }
        }

        return $results ?: null;
    }
}
