#!/usr/bin/env bash

php ./producer.php &
php ./consumer.php &
php ./producer.php &
php ./consumer.php &
