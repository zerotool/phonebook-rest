<?php

namespace PhoneBook\Dictionaries;

use GuzzleHttp\Client;
use Phalcon\Logger\AdapterInterface;

class HostawayApi implements CountryStorage, TimezoneStorage
{
    const BASE_URL = 'https://api.hostaway.com/';
    const COUNTRIES_URI = 'countries';
    const TIMEZONES_URI = 'timezones';

    const REQUEST_TIMEOUT = 5;

    /**
     * @var Client
     */
    private $client;
    /**
     * @var CacheStorage
     */
    private $cache;
    /**
     * @var AdapterInterface
     */
    private $logger;

    public function __construct(CacheStorage $cache, AdapterInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
        $this->client = new Client([
            'base_uri' => static::BASE_URL,
            'timeout' => static::REQUEST_TIMEOUT
        ]);
    }

    /**
     * @return array
     */
    public function loadCountries(): array
    {
        if ($countries = $this->cache->loadCountries()) {
            return $countries;
        }

        try {
            $countries = $this->getResponseResult(self::COUNTRIES_URI);
            $this->cache->saveCountries($countries);
        } catch (\Exception $e) {
            $this->logger->error("Can't load countries");
            $countries = [];
        }

        return $countries;
    }

    /**
     * @return array
     */
    public function loadTimezones(): array
    {
        if ($timezones = $this->cache->loadTimezones()) {
            return $timezones;
        }

        try {
            $timezones = $this->getResponseResult(self::TIMEZONES_URI);
            $this->cache->saveTimezones($timezones);
        } catch (\Exception $e) {
            $this->logger->error("Can't load timezones");
            $timezones = [];
        }

        return $timezones;
    }

    /**
     * @param string $endpoint
     * @return array
     */
    private function getResponseResult(string $endpoint): array
    {
        $response = $this->client->get($endpoint);
        $responseArray = json_decode($response->getBody()->getContents(), true);
        return $responseArray['result'];
    }
}
