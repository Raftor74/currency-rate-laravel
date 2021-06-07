<?php

namespace App\Services\Currency\Providers\CentralRussianBank;

use App\Services\Currency\Providers\CentralRussianBank\Exceptions\InvalidResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class XmlClient
{
    protected $requestClient;

    public function __construct(Client $requestClient)
    {
        $this->requestClient = $requestClient;
    }

    public function fetchContent(): string
    {
        try {
            $response = $this->requestClient->get($this->serviceUrl());
        } catch (GuzzleException $exception) {
            throw new InvalidResponseException($exception->getMessage());
        }

        $content = $response->getBody()->getContents();

        if (empty($content)) {
            throw new InvalidResponseException('Empty server response');
        }

        return $content;
    }

    public function fetchXml(): \SimpleXMLElement
    {
        return new \SimpleXMLElement($this->fetchContent());
    }

    public function serviceUrl(): string
    {
        return 'https://www.cbr.ru/scripts/XML_daily.asp';
    }

}
