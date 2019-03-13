<?php

namespace PhoneBook\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use PhoneBook\Dictionaries\CountryStorage;
use PhoneBook\Dictionaries\TimezoneStorage;

class Contact extends Model
{
    public static $SHOW_FIELDS = ['id', 'first_name', 'last_name', 'phone_number', 'country_code', 'timezone'];

    public function initialize()
    {
        $this->setSource('contacts');
        $this->addBehavior(new SoftDelete(
            [
                'field' => 'deleted',
                'value' => true
            ]
        ));

        $this->skipAttributes(['id', 'inserted_on', 'deleted']);

        $this->addBehavior(
            new Model\Behavior\Timestampable(
                [
                    'beforeUpdate' => [
                        'field' => 'updated_on',
                        'format' => 'Y-m-d H:i:s',
                    ]
                ]
            )
        );
    }

    public function validation()
    {
        $validator = new Validation();

        /** @var CountryStorage|TimezoneStorage $dictionary */
        $dictionary = $this->getDI()['dictionary'];

        $countries = $dictionary->loadCountries();
        $timezones = $dictionary->loadTimezones();

        if (!$countries) {
            $message = new Message(
                "Can't validate data because dictionaries are not loaded",
                'country_code',
                'ValidateDictionaries'
            );
            $this->appendMessage($message);
        }

        if (!$timezones) {
            $message = new Message(
                "Can't validate data because dictionaries are not loaded",
                'timezone',
                'ValidateDictionaries'
            );
            $this->appendMessage($message);
        }

        $validator->add('first_name', new PresenceOf(['message' => 'The first_name is required']));
        $validator->add('last_name', new PresenceOf(['message' => 'The last_name is required']));
        $validator->add('phone_number', new PresenceOf(['message' => 'The phone_number is required']));
        $validator->add('phone_number', new Regex(
                ['pattern' => '/^\+\d+\s\d+\s\d+$/', 'message' => 'Invalid phone format'])
        );
        $validator->add('country_code', new PresenceOf(['message' => 'The country_code is required']));
        $validator->add("country_code", new InclusionIn(
                [
                    "message" => "Invalid country code",
                    "domain" => array_keys($countries),
                ]
            )
        );
        $validator->add('timezone', new PresenceOf(['message' => 'The timezone is required']));

        $validator->add("timezone", new InclusionIn(
                [
                    "message" => "Invalid timezone",
                    "domain" => array_keys($timezones),
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * integer
     */
    public $id;

    /**
     * string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var string
     */
    public $phone_number;

    /**
     * @var string
     */
    public $country_code;

    /**
     * @var string
     */
    public $timezone;

    /**
     * @var \DateTime
     */
    public $inserted_on;

    /**
     * @var \DateTime
     */
    public $updated_on;
}
