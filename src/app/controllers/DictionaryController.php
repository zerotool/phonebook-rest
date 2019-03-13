<?php

namespace PhoneBook\Controllers;

use Phalcon\Mvc\Controller;
use PhoneBook\Dictionaries\CountryStorage;
use PhoneBook\Dictionaries\TimezoneStorage;

/**
 * @property TimezoneStorage|CountryStorage dictionary
 */
class DictionaryController extends Controller
{
    public function countries()
    {
        $this->response->setJsonContent($this->dictionary->loadCountries());
        return $this->response;
    }

    public function timezones()
    {
        $this->response->setJsonContent($this->dictionary->loadTimezones());
        return $this->response;
    }
}
