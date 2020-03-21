#!/bin/bash
# shellcheck disable=SC2164
cd /home/backend;
php artisan migrate -v;
