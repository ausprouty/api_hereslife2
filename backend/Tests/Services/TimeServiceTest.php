<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use App\Services\TimeService;
use RuntimeException;

class TimeServiceTest extends TestCase
{
    private $timeService;
    private $baseDir;
    private $queSentFile;
    private $newTipsSentFile;

    protected function setUp(): void
    {
        $this->baseDir = realpath(__DIR__ . '/../../Storage/Timestamps');
        error_log($this->baseDir);
        if (!file_exists($this->baseDir)) {
            mkdir($this->baseDir, 0777, true);
        }
        
        $this->queSentFile = $this->baseDir . '/QueSent.txt';
        $this->newTipsSentFile = $this->baseDir . '/NewTipsSent.txt';

        $this->timeService = new TimeService($this->queSentFile, $this->newTipsSentFile);
    }

    protected function tearDown(): void
    {
        // Clean up the files after each test
        if (file_exists($this->queSentFile)) {
            unlink($this->queSentFile);
        }
        if (file_exists($this->newTipsSentFile)) {
            unlink($this->newTipsSentFile);
        }
    }

    public function testSetAndGetQueSentTimestamp()
    {
        $timestamp = 1693503600;
        $this->timeService->setTimestamp('QueSent', $timestamp);
        
        $this->assertEquals($timestamp, $this->timeService->getTimestamp('QueSent', 0));
    }

    public function testSetTimestampUsesCurrentTimeIfNotProvided()
    {
        $before = time();
        $this->timeService->setTimestamp('QueSent');
        $after = time();

        $retrievedTimestamp = $this->timeService->getTimestamp('QueSent', 0);

        $this->assertGreaterThanOrEqual($before, $retrievedTimestamp);
        $this->assertLessThanOrEqual($after, $retrievedTimestamp);
    }

    public function testInvalidFilePathThrowsException()
    {
        $this->expectException(RuntimeException::class);

        $invalidFile = '../../etc/passwd';
        $this->timeService->setTimestamp($invalidFile, time());
    }

    public function testGetTimestampReturnsDefaultIfFileDoesNotExist()
    {
        $default = 1234567890;
        $retrievedTimestamp = $this->timeService->getTimestamp('NonExistentFile', $default);

        $this->assertEquals($default, $retrievedTimestamp);
    }
}
