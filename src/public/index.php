<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Micro\Collection as MicroCollection;


error_reporting(E_ALL);

/**
 * Define some useful constants
 */
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
     */
    $di = new FactoryDefault();

    /**
     * Read services
     */
    include APP_PATH . "/config/services.php";

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Micro($di);
    $application->notFound(function () use ($application) {
        $application->response->setStatusCode(404, "Not Found")->sendHeaders();
        echo 'Page not found!';
        die(1);
    });

    $phoneBook = new MicroCollection();
    $phoneBook->setHandler(new \PhoneBook\Controllers\PhoneBookController());
    $phoneBook->setPrefix('/contacts')
        ->get('/{page:\d+}/{limit:\d+}', 'index')
        ->get('/{page:\d+}/{limit:\d+}/{search}', 'search')
        ->get('/{id:\d+}', 'show')
        ->post('/', 'create')
        ->delete('/{id:\d+}', 'delete')
        ->put('/{id:\d+}', 'update');
    $application->mount($phoneBook);

    $dictionaries = new MicroCollection();
    $dictionaries->setHandler(new \PhoneBook\Controllers\DictionaryController());
    $dictionaries->get('/countries', 'countries');
    $dictionaries->get('/timezones', 'timezones');
    $application->mount($dictionaries);

    $application->handle();
} catch (Exception $e) {
    $di['logger']->error($e->getMessage() . ':' . $e->getTraceAsString());
    echo json_encode(['error' => true]);
}
