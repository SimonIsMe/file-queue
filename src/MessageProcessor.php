<?php

namespace FileQueue;

class MessageProcessor
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var DirectoryCreator
     */
    private $directoryCreator;

    /**
     * @var MessageProcessorInterface
     */
    private $messageProcessor;

    /**
     * @var MessageProcessorInterface
     */
    private $fallbackMessageProcessor;

    public function __construct(string $path, DirectoryCreator $directoryCreator, MessageProcessorInterface $messageProcessor, MessageProcessorInterface $fallbackMessageProcessor)
    {
        $this->path = $path;
        $this->directoryCreator = $directoryCreator;
        $this->messageProcessor = $messageProcessor;
        $this->fallbackMessageProcessor = $fallbackMessageProcessor;
    }

    /**
     * @throws \Exception
     */
    public function processMessage(string $queueName, int $secondsLimit): void
    {
        $queuePath = sprintf('%s/%s/', $this->path, $queueName);
        $this->directoryCreator->createDirectory($queuePath);

        $files = scandir($queuePath);

        array_shift($files);
        array_shift($files);

        $now = time();
        foreach ($files as $fileName) {
            if ($now + $secondsLimit < time()) {
                return;
            }

            $filePath = sprintf('%s/%s', $queuePath, $fileName);
            if (!is_file($filePath)) {
                continue;
            }

            if (!$this->setLock($filePath)) {
                continue;
            }

            $content = file_get_contents($filePath);

            try {
                $this->messageProcessor->processMessage($content);
            } catch (\Exception | \Error $exception) {
                $this->fallbackMessageProcessor->processMessage($content);
            }

            unlink($filePath);

            $this->removeLock($filePath);
        }
    }

    private function setLock(string $filePath): bool
    {
        $fileLockPath = sprintf('%s.lock', $filePath);
        return !@mkdir($fileLockPath, 0700);
    }

    private function removeLock(string $filePath): void
    {
        $fileLockPath = sprintf('%s.lock', $filePath);
        rmdir($fileLockPath);
    }
}