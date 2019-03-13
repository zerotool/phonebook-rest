<?php

use Phalcon\Config;

defined('APP_PATH') || define('APP_PATH', realpath('.'));

return new Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'db',
        'username' => 'root',
        'password' => '111111',
        'dbname' => 'phonebook'
    ],
    'redis' => [
        'host' => 'redis',
    ],
    'logger' => [
        'path' => '/var/log/phonebook/error.log',
    ],
    'application' => [
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir' => APP_PATH . '/models/',
        'libraryDir' => APP_PATH . '/library/',
        'baseUri' => '/',
        'cryptSalt' => 'K#Jljrfhduo2f3h98uoBJDHF)*3h89wuhfdsjfhoi398GUB#ofhs0dfhu83D'
    ],
]);
