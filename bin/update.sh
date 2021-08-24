#!/bin/bash

git pull
composer install
./bin/console cache:warmup -e prod
./bin/console d:m:m -n