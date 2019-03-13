<?php

use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use PhoneBook\Dictionaries\CacheStorage;
use PhoneBook\Dictionaries\HostawayApi;

/**
 * Register the global configuration as config
 */
$di->setShared('config', function () {
    return include APP_PATH . '/config/config.php';
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () {
    $config = $this->getConfig();
    return new DbAdapter([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ]);
});

$di->set('dictionary', function () use ($di) {
    // there can be realized dictionary for tests and for dev
    return new HostawayApi(new CacheStorage($di->get('redis')), $this->get('logger'));
});

$di->set('logger', function () {
    $config = $this->getConfig();
    $options = [
        'name' => $config->logger->path,
        'adapter' => 'file',
    ];

    return Phalcon\Logger\Factory::load($options);
});

$di->set('redis', function () {
    $config = $this->getConfig();
    $redis = new Redis();
    $redis->connect($config->redis->host);

    return $redis;
});
