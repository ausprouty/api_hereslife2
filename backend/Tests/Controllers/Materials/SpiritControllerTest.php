<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Materials\SpiritController;
use App\Models\Materials\SpiritModel;
use App\Services\Materials\ResourceService;


class SpiritControllerTest extends TestCase
{
    private $spiritModelMock;
    private $resourceServiceMock;
    private $spiritController;

    protected function setUp(): void
    {
        // Create mocks for SpiritModel and ResourceService
        $this->spiritModelMock = $this->createMock(SpiritModel::class);
        $this->resourceServiceMock = $this->createMock(ResourceService::class);

        // Instantiate SpiritController with the mocked dependencies
        $this->spiritController = new SpiritController($this->spiritModelMock, $this->resourceServiceMock);
    }

    public function testGetTitlesByLanguageNameReturnsArray()
    {
        // Arrange: Define the expected data from the mock
        $expectedData = [
            ['title' => 'Title 1', 'language' => 'English'],
            ['title' => 'Title 2', 'language' => 'Spanish']
        ];

        // Set up the SpiritModel mock to return the expected data
        $this->spiritModelMock
            ->method('getTitlesByLanguageName')
            ->willReturn($expectedData);

        // Act: Call the method on the SpiritController
        $result = $this->spiritController->getTitlesByLanguageName();

        // Assert: Check that the result is an array and matches the expected data
        $this->assertIsArray($result);
        $this->assertEquals($expectedData, $result);
    }

    public function testGetTitlesByLanguageNameContainsValidData()
    {
        // Arrange: Mock return data from the SpiritModel
        $mockData = [
            ['title' => 'Amazing Grace', 'language' => 'English'],
            ['title' => 'Gracia Maravillosa', 'language' => 'Spanish']
        ];

        // Set up the SpiritModel mock
        $this->spiritModelMock
            ->method('getTitlesByLanguageName')
            ->willReturn($mockData);

        // Act: Call the method
        $result = $this->spiritController->getTitlesByLanguageName();

        // Assert: Check the array structure
        foreach ($result as $item) {
            $this->assertArrayHasKey('title', $item, 'Each item should have a "title" key.');
            $this->assertArrayHasKey('language', $item, 'Each item should have a "language" key.');
        }
    }
}
