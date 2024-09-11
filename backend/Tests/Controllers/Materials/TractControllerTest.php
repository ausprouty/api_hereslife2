<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Materials\TractController;
use App\Services\Database\DatabaseService;
use PDOStatement;

class TractControllerTest extends TestCase
{
    private $databaseServiceMock;
    private $tractController;
    private $pdoStatementMock;

    protected function setUp(): void
    {
        // Create a mock for DatabaseService
        $this->databaseServiceMock = $this->createMock(DatabaseService::class);

        // Create a mock for PDOStatement (result of executeQuery)
        $this->pdoStatementMock = $this->createMock(PDOStatement::class);

        // Inject the mock into TractController
        $this->tractController = new TractController($this->databaseServiceMock);
    }

    public function testGetTractsBilingualEnglishReturnsArray()
    {
        // Arrange: Define the expected data
        $expectedTracts = [
            ['title' => 'Amazing Grace'],
            ['title' => 'Gracia Maravillosa']
        ];

        // Set up the PDOStatement mock to return the expected data
        $this->pdoStatementMock
            ->method('fetchAll')
            ->willReturn($expectedTracts);

        // Set up the DatabaseService mock to return the PDOStatement mock
        $this->databaseServiceMock
            ->method('executeQuery')
            ->willReturn($this->pdoStatementMock);

        // Act: Call the method on the TractController
        $result = $this->tractController->getTractsBilingualEnglish();

        // Assert: Check that the result is an array and matches the expected data
        $this->assertIsArray($result);
        $this->assertEquals($expectedTracts, $result);
    }

    public function testGetTractsMonolingualReturnsArray()
    {
        // Arrange: Define the expected data
        $expectedTracts = [
            ['title' => 'Monolingual Tract 1', 'foreign_title_1' => 'Foreign Title 1'],
            ['title' => 'Monolingual Tract 2', 'foreign_title_1' => 'Foreign Title 2']
        ];

        // Set up the PDOStatement mock to return the expected data
        $this->pdoStatementMock
            ->method('fetchAll')
            ->willReturn($expectedTracts);

        // Set up the DatabaseService mock to return the PDOStatement mock
        $this->databaseServiceMock
            ->method('executeQuery')
            ->willReturn($this->pdoStatementMock);

        // Act: Call the method on the TractController
        $result = $this->tractController->getTractsMonolingual();

        // Assert: Check that the result is an array and matches the expected data
        $this->assertIsArray($result);
        $this->assertEquals($expectedTracts, $result);
    }

    public function testGetTractsToViewReturnsArray()
    {
        // Arrange: Define the expected data
        $expectedTracts = [
            ['lang1' => 'English'],
            ['lang1' => 'Spanish']
        ];

        // Set up the PDOStatement mock to return the expected data
        $this->pdoStatementMock
            ->method('fetchAll')
            ->willReturn($expectedTracts);

        // Set up the DatabaseService mock to return the PDOStatement mock
        $this->databaseServiceMock
            ->method('executeQuery')
            ->willReturn($this->pdoStatementMock);

        // Act: Call the method on the TractController
        $result = $this->tractController->getTractsToView();

        // Assert: Check that the result is an array and matches the expected data
        $this->assertIsArray($result);
        $this->assertEquals($expectedTracts, $result);
    }
}
