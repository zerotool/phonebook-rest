<?php

namespace PhoneBook\Dictionaries;

interface TimezoneStorage
{
    /**
     * @return array
     */
    public function loadTimezones(): array;
}
