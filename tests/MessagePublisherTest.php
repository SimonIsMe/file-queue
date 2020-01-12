<?php

namespace tests;

use FileQueue\DirectoryCreator;
use FileQueue\MessagePublisher;
use PHPUnit\Framework\TestCase;

class MessagePublisherTest extends TestCase
{
    public function testPublish(): void
    {
        $path =  __DIR__ . '/../var/';

        $messagePublisher = new MessagePublisher(
            new DirectoryCreator(),
            $path
        );

        $messagePublisher->publish('queue_1', 'message 1.1');
        $messagePublisher->publish('queue_1', 'message 1.2');
        $messagePublisher->publish('queue_2', 'message 2');

        $queueDirectory_1 = scandir($path . '/queue_1');
        $this->assertEquals('message 1.1', file_get_contents($path . '/queue_1/' . $queueDirectory_1[2]));
        $this->assertEquals('message 1.2', file_get_contents($path . '/queue_1/' . $queueDirectory_1[3]));

        $queueDirectory_1 = scandir($path . '/queue_2');
        $this->assertEquals('message 2', file_get_contents($path . '/queue_2/' . $queueDirectory_1[2]));

        exec('rm -r ' . $path . '/*');
    }
}