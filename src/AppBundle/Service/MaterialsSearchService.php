<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;

class MaterialsSearchService
{
    private $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function search($query)
    {
        $client = new Client();
        $response = $client->request('GET', $this->configuration['url'], [
            'query' => [
                'action' => 'search',
                'agency' => $this->configuration['agency'],
                'profile' => $this->configuration['profile'],
                'query' => $query,
                'stepValue' => 10,
            ],
        ]);

        $results = [];

        if ($response->getStatusCode() === 200) {
            $data = new \SimpleXMLElement((string) $response->getBody());
            foreach ($data->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body') as $body) {
                foreach ($body->searchResponse->result->searchResult as $result) {
                    foreach ($result->collection->object as $object) {
                        $record = $object->xpath('dkabm:record')[0];
                        $title = $record->xpath('dc:title');
                        $description = $record->xpath('dc:description');
                        if ($title) {
                            $results[] = [
                                'identifier' => (string) $object->identifier,
                                'title' => (string) $title[0],
                                'name' => (string) $title[0],
                                'description' => $description ? (string) $description[0] : null,
                            ];
                        }
                    }
                }
            }
        }

        return $results ?: null;
    }

    private function unnamespaceXml(string $xml)
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        //$root = $dom->createElement('root');
        //$dom->appendChild($root);
        $sxe = new \SimpleXMLElement($xml);
        foreach ($sxe->children('http://schemas.xmlsoap.org/soap/envelope/') as $child) {
            $node = $dom->importNode(dom_import_simplexml($child), true);
            $dom->appendChild($node);
        }

        $sxe = new \SimpleXMLElement($dom->saveXML());
        $root = $dom->childNodes->item(0);
        foreach ($sxe->getNamespaces(true) as $name => $uri) {
            $root->removeAttributeNS($uri, $name);
        }

        return new \SimpleXMLElement($dom->saveXML());
    }
}
