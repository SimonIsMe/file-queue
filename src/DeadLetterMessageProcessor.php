<?php


namespace FileQueue;


class DeadLetterMessageProcessor implements MessageProcessorInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $queueName;

    public function __construct(string $path, string $queueName)
    {
        $this->path = $path;
        $this->queueName = $queueName;
    }

    public function processMessage(string $message): void
    {
        $messagePublisher = new MessagePublisher($this->path);
        $messagePublisher->publish($this->queueName, $message);
    }
}