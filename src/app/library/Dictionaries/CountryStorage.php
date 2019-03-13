<?php

namespace PhoneBook\Dictionaries;

interface CountryStorage
{
    /**
     * @return array
     */
    public function loadCountries(): array;
}
