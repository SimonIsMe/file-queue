<?php


namespace FileQueue;


interface MessageProcessorInterface
{
    /**
     * @throws \Exception
     */
    public function processMessage(string $message): void;
}