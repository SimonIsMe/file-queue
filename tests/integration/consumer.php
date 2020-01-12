<?php

use FileQueue\DirectoryCreator;
use FileQueue\EmptyMessageProcessor;
use FileQueue\MessageProcessor;
use FileQueue\MessageProcessorInterface;
use FileQueue\MessagePublisher;

include __DIR__ . '/../../vendor/autoload.php';

class Consumer implements MessageProcessorInterface {

    public $countProcessedMessages = 0;

    public function processMessage(string $message): void
    {
        $this->countProcessedMessages++;
    }
}

$consumer = new Consumer();

$messageProcessor = new MessageProcessor(
    __DIR__ . '/../../var/',
    new DirectoryCreator(),
    $consumer,
    new EmptyMessageProcessor()
);
$messageProcessor->processMessage('queueName', 30);

echo 'Number of all processed messages: '. $consumer->countProcessedMessages . "\n\n";