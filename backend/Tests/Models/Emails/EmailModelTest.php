<?php

use PHPUnit\Framework\TestCase;
use App\Models\Emails\EmailModel;
use App\Services\Database\DatabaseService;
use PDO;
use PDOStatement;

class EmailModelTest extends TestCase
{
    private $databaseServiceMock;
    private $emailModel;
    private $pdoStatementMock;

    protected function setUp(): void
    {
        // Mock DatabaseService
        $this->databaseServiceMock = $this->createMock(DatabaseService::class);

        // Mock PDOStatement (result of executeQuery/executeUpdate)
        $this->pdoStatementMock = $this->createMock(PDOStatement::class);

        // Inject the mock DatabaseService into EmailModel
        $this->emailModel = new EmailModel($this->databaseServiceMock);
    }

    public function testCreate()
    {
        // Arrange: Define the expected data and mock the executeUpdate method
        $data = [
            'subject' => 'Test Subject',
            'body' => 'Test Body',
            'plain_text_only' => false,
            'headers' => '',
            'template' => 'default',
            'series' => 'test-series',
            'sequence' => 1,
            'params' => ''
        ];

        $this->databaseServiceMock->expects($this->once())
                                  ->method('executeUpdate')
                                  ->with($this->anything(), $this->anything())
                                  ->willReturn(1); // Simulate successful insert

        // Act: Call create
        $result = $this->emailModel->create($data);

        // Assert: Check that result is the expected insert response (e.g., success)
        $this->assertEquals(1, $result);
    }

    public function testUpdate()
    {
        // Arrange: Define the expected data and mock the executeUpdate method
        $data = [
            'subject' => 'Updated Subject',
            'body' => 'Updated Body',
            'plain_text_only' => true,
            'headers' => '',
            'template' => 'custom',
            'series' => 'test-series',
            'sequence' => 2,
            'params' => 'custom-params'
        ];
        $id = 1;

        $this->databaseServiceMock->expects($this->once())
                                  ->method('executeUpdate')
                                  ->with($this->anything(), $this->anything())
                                  ->willReturn(1); // Simulate successful update

        // Act: Call update
        $result = $this->emailModel->update($id, $data);

        // Assert: Check that the result is the expected response (e.g., success)
        $this->assertEquals(1, $result);
    }

    public function testDelete()
    {
        // Arrange: Mock the executeUpdate method for delete
        $id = 1;
        $this->databaseServiceMock->expects($this->once())
                                  ->method('executeUpdate')
                                  ->with($this->anything(), [':id' => $id])
                                  ->willReturn(1); // Simulate successful delete

        // Act: Call delete
        $result = $this->emailModel->delete($id);

        // Assert: Check that the result is the expected response (e.g., success)
        $this->assertEquals(1, $result);
    }

    public function testFindById()
    {
        // Arrange: Mock the executeQuery and fetch method for findById
        $id = 1;
        $expectedData = ['id' => $id, 'subject' => 'Test Email'];

        $this->databaseServiceMock->expects($this->once())
                                  ->method('executeQuery')
                                  ->with($this->anything(), [':id' => $id])
                                  ->willReturn($this->pdoStatementMock);

        $this->pdoStatementMock->expects($this->once())
                               ->method('fetch')
                               ->willReturn($expectedData);

        // Act: Call findById
        $result = $this->emailModel->findById($id);

        // Assert: Check that the result matches the expected data
        $this->assertEquals($expectedData, $result);
    }

    public function testFindAll()
    {
        // Arrange: Mock the executeQuery and fetchAll method for findAll
        $expectedData = [
            ['id' => 1, 'subject' => 'Email 1'],
            ['id' => 2, 'subject' => 'Email 2']
        ];

        $this->databaseServiceMock->expects($this->once())
                                  ->method('executeQuery')
                                  ->willReturn($this->pdoStatementMock);

        $this->pdoStatementMock->expects($this->once())
                               ->method('fetchAll')
                               ->willReturn($expectedData);

        // Act: Call findAll
        $result = $this->emailModel->findAll();

        // Assert: Check that the result matches the expected data
        $this->assertEquals($expectedData, $result);
    }

    public function testFindOneInSeries()
    {
        // Arrange: Mock the executeQuery and fetch method for findOneInSeries
        $series = 'test-series';
        $sequence = 1;
        $expectedData = ['id' => 1, 'subject' => 'Test Email'];

        $this->databaseServiceMock->expects($this->once())
                                  ->method('executeQuery')
                                  ->with($this->anything(), [':series' => $series, ':sequence' => $sequence])
                                  ->willReturn($this->pdoStatementMock);

        $this->pdoStatementMock->expects($this->once())
                               ->method('fetch')
                               ->willReturn($expectedData);

        // Act: Call findOneInSeries
        $result = $this->emailModel->findOneInSeries($series, $sequence);

        // Assert: Check that the result matches the expected data
        $this->assertEquals($expectedData, $result);
    }

    public function testGetRecentBlogTitles()
    {
        // Arrange: Mock the executeQuery and fetchAll method for getRecentBlogTitles
        $number = 3;
        $expectedData = [
            ['subject' => 'Blog 1', 'id' => 1],
            ['subject' => 'Blog 2', 'id' => 2]
        ];

        $this->databaseServiceMock->expects($this->once())
                                  ->method('executeQuery')
                                  ->willReturn($this->pdoStatementMock);

        $this->pdoStatementMock->expects($this->once())
                               ->method('fetchAll')
                               ->willReturn($expectedData);

        // Act: Call getRecentBlogTitles
        $result = $this->emailModel->getRecentBlogTitles($number);

        // Assert: Check that the result matches the expected data
        $this->assertEquals($expectedData, $result);
    }
}
