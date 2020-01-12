<?php

namespace FileQueue;

class CanNotCreateDirectoryException extends \Exception
{
    public function __construct(string $path)
    {
        parent::__construct(
            sprintf('The script can not create a directory: %s', $path)
        );
    }
}