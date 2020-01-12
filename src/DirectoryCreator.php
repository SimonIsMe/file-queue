<?php

namespace FileQueue;

class DirectoryCreator
{
    /**
     * @throws CanNotCreateDirectoryException
     * @throws PathIsNotDirectoryException
     * @throws PathIsNotWritableDirectoryException
     */
    public function createDirectory(string $path): void
    {
        if (!file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new CanNotCreateDirectoryException($path);
            }
        }

        if (!is_dir($path)) {
            throw new PathIsNotDirectoryException($path);
        }

        if (!is_writeable($path)) {
            throw new PathIsNotWritableDirectoryException($path);
        }
    }
}