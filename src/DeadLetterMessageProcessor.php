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

    /**
     * @var MessagePublisher
     */
    private $messagePublisher;

    public function __construct(string $path, string $queueName)
    {
        $this->path = $path;
        $this->queueName = $queueName;
        $this->messagePublisher = new MessagePublisher(new DirectoryCreator(), $this->path);
    }

    public function processMessage(string $message): void
    {
        $this->messagePublisher->publish($this->queueName . '_dead_letter', $message);
    }
}