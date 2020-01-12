<?php

namespace FileQueue;

class PathIsNotDirectoryException extends \Exception
{
    public function __construct(string $path)
    {
        parent::__construct(
            sprintf('%s is not a directory.', $path)
        );
    }
}