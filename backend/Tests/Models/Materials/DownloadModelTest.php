<?php

use PHPUnit\Framework\TestCase;
use App\Models\Materials\DownloadModel;
use App\Services\Database\DatabaseService;

/**
 * DownloadModelTest
 *
 * Unit test for the DownloadModel class.
 */
class DownloadModelTest extends TestCase
{
    private $downloadModel;
    private $databaseServiceMock;

    /**
     * Setup method called before each test to initialize objects.
     */
    protected function setUp(): void
    {
        // Create a mock of DatabaseService
        $this->databaseServiceMock = $this->createMock(DatabaseService::class);

        // Inject the mock into DownloadModel
        $this->downloadModel = new DownloadModel($this->databaseServiceMock);
    }

    /**
     * Test setValues method to ensure properties are correctly assigned.
     */
    public function testSetValues()
    {
        // Define input data
        $data = [
            'champion_id' => 1,
            'file_name' => 'example_file.pdf',
            'download_date' => 1632025680,
            'requested_tips' => 'Tip 1',
            'sent_tips' => 'Tip 2',
            'file_id' => 123,
            'elapsed_months' => 2,
            'tip' => 'Sample Tip',
            'tip_detail' => 'Sample Tip Details',
        ];

        // Set values using the method
        $this->downloadModel->setValues($data);

        // Assert that the properties were set correctly
        $this->assertEquals(1, $this->downloadModel->champion_id);
        $this->assertEquals('example_file.pdf', $this->downloadModel->file_name);
        $this->assertEquals(1632025680, $this->downloadModel->download_date);
        $this->assertEquals('Tip 1', $this->downloadModel->requested_tips);
        $this->assertEquals('Tip 2', $this->downloadModel->sent_tips);
        $this->assertEquals(123, $this->downloadModel->file_id);
        $this->assertEquals(2, $this->downloadModel->elapsed_months);
        $this->assertEquals('Sample Tip', $this->downloadModel->tip);
        $this->assertEquals('Sample Tip Details', $this->downloadModel->tip_detail);
    }

    /**
     * Test the insert method to verify that the correct query is executed and an ID is returned.
     */
    public function testInsert()
    {
        // Define expected query parameters
        $data = [
            'champion_id' => 1,
            'file_name' => 'example_file.pdf',
            'download_date' => 1632025680,
            'requested_tips' => 'Tip 1',
            'sent_tips' => 'Tip 2',
            'file_id' => 123,
            'elapsed_months' => 2,
            'tip' => 'Sample Tip',
            'tip_detail' => 'Sample Tip Details',
        ];

        // Set values using the method
        $this->downloadModel->setValues($data);

        // Define the query that should be executed
        $query = "INSERT INTO hl_downloads 
                  (champion_id, file_name, download_date, requested_tips, sent_tips, file_id, elapsed_months, tip, tip_detail) 
                  VALUES 
                  (:champion_id, :file_name, :download_date, :requested_tips, :sent_tips, :file_id, :elapsed_months, :tip, :tip_detail)";
        
        $params = [
            ':champion_id' => 1,
            ':file_name' => 'example_file.pdf',
            ':download_date' => 1632025680,
            ':requested_tips' => 'Tip 1',
            ':sent_tips' => 'Tip 2',
            ':file_id' => 123,
            ':elapsed_months' => 2,
            ':tip' => 'Sample Tip',
            ':tip_detail' => 'Sample Tip Details'
        ];

        // Mock the executeUpdate and getLastInsertId methods
        $this->databaseServiceMock
            ->expects($this->once())
            ->method('executeUpdate')
            ->with($query, $params);

        $this->databaseServiceMock
            ->expects($this->once())
            ->method('getLastInsertId')
            ->willReturn('10');

        // Call the insert method and assert that the returned ID is correct
        $insertedId = $this->downloadModel->insert();
        $this->assertEquals(10, (int)$insertedId);
    }
}
