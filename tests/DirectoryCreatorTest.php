<?php

namespace tests;

use FileQueue\DirectoryCreator;
use PHPUnit\Framework\TestCase;

class DirectoryCreatorTest extends TestCase
{
    public function tearDown(): void
    {
        exec('rm -r ' . __DIR__ . '/../var/*');
    }

    public function testCreateDirectory()
    {
        $directoryCreator = new DirectoryCreator();
        $directoryCreator->createDirectory(__DIR__ . '/../var/abc');

        $this->assertTrue(is_dir(__DIR__ . '/../var/abc'));
        $this->assertTrue(is_writeable(__DIR__ . '/../var/abc'));
    }

    public function testCreateDirectories()
    {
        $directoryCreator = new DirectoryCreator();
        $directoryCreator->createDirectory(__DIR__ . '/../var/abc/def');

        $this->assertTrue(is_dir(__DIR__ . '/../var/abc'));
        $this->assertTrue(is_writeable(__DIR__ . '/../var/abc'));

        $this->assertTrue(is_dir(__DIR__ . '/../var/abc/def'));
        $this->assertTrue(is_writeable(__DIR__ . '/../var/abc/def'));
    }
}