<?php

namespace FileQueue;

class MessagePublisher
{
    /**
     * @var DirectoryCreator
     */
    private $directoryCreator;

    /**
     * @var string
     */
    private $path;

    public function __construct(DirectoryCreator $directoryCreator, string $path)
    {
        $this->directoryCreator = $directoryCreator;
        $this->path = $path;
    }

    /**
     * @throws CanNotCreateDirectoryException
     * @throws PathIsNotDirectoryException
     * @throws PathIsNotWritableDirectoryException
     */
    public function publish(string $queueName, string $message): void
    {
        $queuePath = sprintf('%s/%s/', $this->path, $queueName);

        $this->directoryCreator->createDirectory($queuePath);

        $filePath = sprintf('%s/%s/%s-%s', $this->path, $queueName, microtime(true), rand(0, 999999999999));
        file_put_contents($filePath, $message);
    }
}