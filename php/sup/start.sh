#!/bin/sh
echo 'php-fpm - start'
php-fpm
echo 'supervisord start'
/usr/bin/supervisord
