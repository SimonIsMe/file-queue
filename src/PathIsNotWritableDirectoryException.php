<?php

namespace FileQueue;

class PathIsNotWritableDirectoryException extends \Exception
{
    public function __construct(string $path)
    {
        parent::__construct(
            sprintf('%s is not writable directory.', $path)
        );
    }
}