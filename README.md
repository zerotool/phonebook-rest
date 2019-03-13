# Phone Book REST Service

![Phone Book REST Service Logo](https://raw.githubusercontent.com/zerotool/phonebook-rest/master/src/public/img/lologo.png)

Implementation of phonebook REST functionality.

Tecnology stack:
* Docker
* PhalconPHP 3.4 
* PHP 7.2 
* Nginx 
* MySQL 5.6 + Adminer 
* Redis
* Codeception

## Requirements
1. Docker, docker-compose.
2. Http ports `8085`, `8086` available on the host machine.

## Install

Run to setup the project locally:
```
git clone https://github.com/zerotool/phonebook-rest.git
cd phonebook-rest
./provisioning.sh
```

It will bootstrap the project into a set of docker containters and run the tests.

### Troubleshooting

Sometimes install process can take 1-2 minutes to start up MySQL:

```
=== Waiting for db to initialize
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
.ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
.ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
.ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
.ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
.ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: YES)
.ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: YES)
.ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: YES)
.ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: YES)
.ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: YES)
.ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: YES)
.ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' (2)
.=== Running database migrations

Phalcon DevTools (3.4.0)

                                                    
  Success: Version 1.0.0 was successfully migrated  
```

That's ok, just wait ¯\\_(ツ)_/¯ You can safely run `./provisioning.sh` multiple times in a row to 
retry/resume/debug the process.

## Available URLs:

* http://localhost:8085/ - REST Api base URL
* http://localhost:8086/ - adminer

Adminer credentials:<br>
* System: `MySQL`<br>
* Server: `db`<br>
* Username: `root`<br>
* Password: `111111`<br>
* DB: `phonebook`

## Usage

* GET `/contacts/{page}/{limit}`
* GET `/contacts/{page}/{limit}/{name_query}`
* GET `/contacts/{id}`
* POST `/contacts`
```
{
	"first_name": "Stanislav",
	"last_name": "Erokhin",
	"phone_number": "+12 223 444224455",
	"country_code": "RU",
	"timezone": "Europe/Moscow"
}
```
* DELETE `/contacts/{id}`
* PUT `/contacts/{id}` (payload is the same as POST)
* GET `/countries` (Redis cache used)
* GET `/timezones` (Redis cache used)

## Codeception

Run all tests under the folder:
```
$ docker-compose run --rm codecept run
```

## TODO

1. Add IaC deployment script.
2. Add DB indexes on `contacts` fields used in search.
3. Add more unit/acceptance tests.

## Contact Information

[st.erokhin@gmail.com](mailto:st.erokhin@gmail.com)

Copyright 2019 Stanislav Erokhin
