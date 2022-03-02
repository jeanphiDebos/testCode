#!/bin/bash

set -e

cd "$( dirname "${BASH_SOURCE[0]}" )"

export COMPOSER_HOME="/home/eparapheur/.composer"

curl -sS https://getcomposer.org/installer | php
php composer.phar install
