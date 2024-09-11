<?php

use PHPUnit\Framework\TestCase;
use App\Models\Materials\SpiritModel;
use App\Services\Database\DatabaseService;
use PDOStatement;

class SpiritModelTest extends TestCase
{
    private $databaseServiceMock;
    private $spiritModel;
    private $pdoStatementMock;

    protected function setUp(): void
    {
        // Mock DatabaseService
        $this->databaseServiceMock = $this->createMock(DatabaseService::class);

        // Mock PDOStatement (result of executeQuery)
        $this->pdoStatementMock = $this->createMock(PDOStatement::class);

        // Instantiate SpiritModel with the mocked DatabaseService
        $this->spiritModel = new SpiritModel($this->databaseServiceMock);
    }

    public function testGetTitlesByLanguageName()
    {
        // Arrange: Mock the executeQuery method to return language names
        $expectedResults = [
            ['languageName' => 'English'],
            ['languageName' => 'Spanish'],
        ];

        $this->databaseServiceMock->expects($this->once())
            ->method('executeQuery')
            ->with($this->anything())
            ->willReturn($this->pdoStatementMock);

        $this->pdoStatementMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedResults);

        // Act: Call the method
        $result = $this->spiritModel->getTitlesByLanguageName();

        // Assert: Check that the result matches the expected values
        $this->assertEquals($expectedResults, $result);
    }

    public function testGetByLanguageName()
    {
        // Arrange: Mock the executeQuery method to return results by language name
        $expectedResults = [
            (object) [
                'id' => 1,
                'name' => 'Spirit A',
                'webpage' => 'https://example.com/spiritA',
                'images' => ['img1', 'img2'],
                'hlId' => 123,
                'valid' => true,
                'promo' => 'promo text'
            ]
        ];

        $this->databaseServiceMock->expects($this->once())
            ->method('executeQuery')
            ->with($this->anything(), [':languageName' => 'English'])
            ->willReturn($this->pdoStatementMock);

        $this->pdoStatementMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedResults);

        // Act: Call the method
        $result = $this->spiritModel->getByLanguageName('English');

        // Assert: Check that the result matches the expected values
        $this->assertEquals($expectedResults, $result);
    }

    public function testLoadFromRecord()
    {
        // Arrange: Create a mock record to populate the model properties
        $record = (object) [
            'id' => 1,
            'name' => 'Spirit A',
            'webpage' => 'https://example.com/spiritA',
            'images' => ['img1', 'img2'],
            'hlId' => 123,
            'valid' => true,
            'promo' => 'promo text'
        ];

        // Act: Load the model from the record
        $this->spiritModel->loadFromRecord($record);

        // Assert: Verify that the model's properties are correctly populated
        $this->assertEquals(1, $this->spiritModel->getId());
        $this->assertEquals('Spirit A', $this->spiritModel->getName());
        $this->assertEquals('https://example.com/spiritA', $this->spiritModel->getWebpage());
        $this->assertEquals(['img1', 'img2'], $this->spiritModel->getImages());
        $this->assertEquals(123, $this->spiritModel->getHlId());
        $this->assertTrue($this->spiritModel->isValid());
        $this->assertEquals('promo text', $this->spiritModel->getPromo());
    }
}
