<?php

namespace PhoneBook\Dictionaries;

class CacheStorage implements CountryStorage, TimezoneStorage
{
    /** one day */
    const TTL = 86400;

    const TIMEZONES_KEY = 'cache_timezones';
    const COUNTRIES_KEY = 'cache_countries';

    private $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @return array
     */
    public function loadCountries(): array
    {
        return json_decode($this->redis->get(self::COUNTRIES_KEY), true) ?: [];
    }

    /**
     * @return array
     */
    public function loadTimezones(): array
    {
        return json_decode($this->redis->get(self::TIMEZONES_KEY), true) ?: [];
    }

    /**
     * @param array $countries
     * @return mixed
     */
    public function saveCountries(array $countries)
    {
        return json_decode($this->redis->setex(self::COUNTRIES_KEY, self::TTL, json_encode($countries)), true);
    }

    /**
     * @param array $timezones
     * @return mixed
     */
    public function saveTimezones(array $timezones)
    {
        return json_decode($this->redis->setex(self::TIMEZONES_KEY, self::TTL, json_encode($timezones)), true);
    }
}
