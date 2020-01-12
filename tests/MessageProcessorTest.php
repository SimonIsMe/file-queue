<?php

namespace tests;

use FileQueue\DirectoryCreator;
use FileQueue\MessageProcessor;
use FileQueue\MessageProcessorInterface;
use FileQueue\MessagePublisher;
use PHPUnit\Framework\TestCase;

class MessageProcessorTest extends TestCase
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var MessageProcessorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $messageProcessorMock;

    /**
     * @var MessageProcessorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $fallbackMessageProcessorMock;

    /**
     * @var MessageProcessor
     */
    private $messageProcessor;

    public function setUp(): void
    {
        $this->path = __DIR__ . '/../var/';

        $messagePublisher = new MessagePublisher(
            new DirectoryCreator(),
            $this->path
        );

        $messagePublisher->publish('queueName', 'message 1');
        $messagePublisher->publish('queueName', 'message 2');
        $messagePublisher->publish('queueName', 'message 3');

        $this->messageProcessorMock = $this->createMock(MessageProcessorInterface::class);

        $this->fallbackMessageProcessorMock = $this->createMock(MessageProcessorInterface::class);

        $this->messageProcessor = new MessageProcessor(
            $this->path,
            new DirectoryCreator(),
            $this->messageProcessorMock,
            $this->fallbackMessageProcessorMock
        );
    }

    public function tearDown(): void
    {
        exec('rm -r ' . __DIR__ . '/../var/*');
    }

    public function testProcessingInTheRightOrder(): void
    {
        $expectedValues = [
            'message 1',
            'message 2',
            'message 3',
        ];

        $this->messageProcessorMock
            ->expects($this->exactly(3))
            ->method('processMessage')
            ->willReturnCallback(function(string $message) use (&$expectedValues) {
                $this->assertEquals(current($expectedValues), $message);
                next($expectedValues);
            });

        $this->messageProcessor->processMessage('queueName', 30);
    }

    public function testProcessingTillTimeProcessWillBeFinished(): void
    {
        $this->messageProcessorMock
            ->expects($this->exactly(1))
            ->method('processMessage')
            ->willReturnCallback(function() {
                sleep(3);
            });

        $this->messageProcessor->processMessage('queueName', 1);
    }

    public function testProcessingWhenExceptionIsThrew(): void
    {
        $this->messageProcessorMock->method('processMessage')->willThrowException(new \Exception());
        $this->fallbackMessageProcessorMock->expects($this->at(1))->method('processMessage');

        $this->messageProcessor->processMessage('queueName', 30);
    }


    public function testProcessingWhenErrorIsThrew(): void
    {
        $this->messageProcessorMock->method('processMessage')->willThrowException(new \Error());
        $this->fallbackMessageProcessorMock->expects($this->at(1))->method('processMessage');

        $this->messageProcessor->processMessage('queueName', 30);
    }

    public function testManyConsumers(): void
    {

    }
}