<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Materials\SpiritController;
use App\Models\Materials\SpiritModel;
use App\Services\Materials\ResourceService;

class SpiritTitlesTest extends TestCase
{
    protected $spiritModel;
    protected $resourceService;
    protected $spiritController;

    protected function setUp(): void
    {
        // Create mocks for the SpiritModel and ResourceService
        $this->spiritModel = $this->createMock(SpiritModel::class);
        $this->resourceService = $this->createMock(ResourceService::class);

        // Instantiate the SpiritController with mocked dependencies
        $this->spiritController = new SpiritController($this->spiritModel, $this->resourceService);
    }

    public function testGetTitlesByLanguageNameReturnsArray()
    {
        // Define the expected output (a mock array of titles)
        $expectedData = [
            ['title' => 'Tract 1', 'language' => 'English'],
            ['title' => 'Tract 2', 'language' => 'French'],
            ['title' => 'Tract 3', 'language' => 'Spanish'],
        ];

        // Mock the getTitlesByLanguageName method to return the expected data
        $this->spiritModel
            ->method('getTitlesByLanguageName')
            ->willReturn($expectedData);

        // Call the method and store the result
        $result = $this->spiritController->getTitlesByLanguageName();

        // Assert that the result is an array
        $this->assertIsArray($result, 'Expected result to be an array');
        
        // Assert that the result matches the expected data
        $this->assertEquals($expectedData, $result, 'Expected data did not match');
    }
}
