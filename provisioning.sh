#!/bin/bash

# BEFORE RUNNING THIS
# Install docker for mac https://download.docker.com/mac/stable/Docker.dmg

# Useful commands:
# REMOVE ALL containers and volumes (wow, danger):
# docker rm -f $(docker ps -a -q) ; docker volume rm $(docker volume ls -q)
# REMOVE ALL project data:
# rm src/.phalcon/migration-version
# clean /mysql/data (except .gitkeep)

set -eu pipefail # turn on strict mode

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
PHONEBOOK_PATH=/var/www
DOCKER_COMPOSE_PATH=$DIR/docker-compose.yml
PHP_CONTAINER_NAME=php

printf '=== Checking prerequisites \360\237\246\204\n'
command -v docker-compose >/dev/null 2>&1 || {
    echo >&2 "I require docker-compose but it's not installed.";
    echo >&2 "Please download and install https://download.docker.com/mac/stable/Docker.dmg";
    exit 1;
}

echo === Standing up docker
docker-compose -f $DOCKER_COMPOSE_PATH up -d --build  --remove-orphans

testConnection (){
  if [ $# -lt 3 ]
    then
      echo 'No enough arguments supplied. Sample call: "testConnection serverName connectionString testQuery"'
      exit
  fi

  serverName=$1
  serverConnection=$2
  testQuery=$3

  echo
  echo === Waiting for $serverName to initialize
  maxTries=40
  remainingRetries=$maxTries

  while [ $remainingRetries -gt 0 ] && ! docker-compose -f $DOCKER_COMPOSE_PATH exec -T $serverName $serverConnection "$testQuery" > /dev/null; do
    remainingRetries=$((remainingRetries - 1))
    sleep 3
    printf .
  done
  if [ $remainingRetries -le 0 ]; then
      echo >&2 "error: unable to contact $serverName after $maxTries tries. Execute script one more time."
      exit 1
  fi
}

testConnection 'db' 'mysql -u root -p111111' '-e use phonebook;'

echo === Running database migrations
docker-compose -f $DOCKER_COMPOSE_PATH exec -T $PHP_CONTAINER_NAME /usr/bin/env phalcon migration run

echo === Environment should be setup and ready to use.;
echo === You may now navigate to http://localhost:8085;

echo === Running tests;
docker-compose run --rm codecept run
