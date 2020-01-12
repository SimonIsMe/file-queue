<?php

namespace FileQueue;

class EmptyMessageProcessor implements MessageProcessorInterface
{
    public function processMessage(string $message): void
    {
    }
}