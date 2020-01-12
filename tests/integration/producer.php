<?php

use FileQueue\DirectoryCreator;
use FileQueue\MessagePublisher;

include __DIR__ . '/../../vendor/autoload.php';

$messagePublisher = new MessagePublisher(
    new DirectoryCreator(),
    __DIR__ . '/../../var/'
);

for ($i = 0; $i < 10000; $i++) {
    $messagePublisher->publish('queueName', 'message');
}