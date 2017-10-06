#!/usr/bin/env bash

set -e

echo "** Running phpunit (exclude integration)"
vendor/bin/phpunit --exclude-group integration
