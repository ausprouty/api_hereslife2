<?php

use PHPUnit\Framework\TestCase;
use App\Models\Materials\DownloadModel;
use App\Services\DatabaseService;

class DownloadModelTest extends TestCase
{
    private $databaseServiceMock;
    private $downloadModel;

    protected function setUp(): void
    {
        // Mock the DatabaseService
        $this->databaseServiceMock = $this->createMock(DatabaseService::class);

        // Inject the mocked DatabaseService into the DownloadModel
        $this->downloadModel = new DownloadModel($this->databaseServiceMock);
    }

    public function testSetValues()
    {
        // Arrange: Define the parameters
        $params = [
            'id' => 1,
            'champion_id' => 123,
            'file_name' => 'testfile.pdf',
            'download_date' => time(),
            'requested_tips' => 'test tips',
            'sent_tips' => 'sent tips',
            'file_id' => 45,
            'elapsed_months' => 3,
            'tip' => 'test tip',
            'tip_detail' => 'detailed tip'
        ];

        // Act: Set the values in the model
        $this->downloadModel->setValues($params);

        // Assert: Check that the values were correctly set
        $this->assertEquals(1, $this->downloadModel->getId());
        $this->assertEquals(123, $this->downloadModel->getChampionId());
        $this->assertEquals('testfile.pdf', $this->downloadModel->getFileName());
        $this->assertEquals($params['download_date'], $this->downloadModel->getDownloadDate());
        $this->assertEquals('test tips', $this->downloadModel->getRequestedTips());
        $this->assertEquals('sent tips', $this->downloadModel->getSentTips());
        $this->assertEquals(45, $this->downloadModel->getFileId());
        $this->assertEquals(3, $this->downloadModel->getElapsedMonths());
        $this->assertEquals('test tip', $this->downloadModel->getTip());
        $this->assertEquals('detailed tip', $this->downloadModel->getTipDetail());
    }

    public function testInsert()
    {
        // Arrange: Set values
        $this->downloadModel->setValues([
            'champion_id' => 123,
            'file_name' => 'testfile.pdf',
            'download_date' => time(),
            'requested_tips' => 'test tips',
            'sent_tips' => 'sent tips',
            'file_id' => 45,
            'elapsed_months' => 3,
            'tip' => 'test tip',
            'tip_detail' => 'detailed tip'
        ]);

        // Mock the executeQuery method to return success
        $this->databaseServiceMock->expects($this->once())
            ->method('executeQuery')
            ->with($this->anything(), $this->anything())
            ->willReturn(true);  // Simulate successful insert

        // Act: Call the insert method
        $result = $this->downloadModel->insert();

        // Assert: Verify that the insert was called successfully
        $this->assertTrue($result);
    }

    public function testUpdate()
    {
        // Arrange: Set values, including ID for update
        $this->downloadModel->setValues([
            'id' => 1,
            'champion_id' => 123,
            'file_name' => 'updatedfile.pdf',
            'download_date' => time(),
            'requested_tips' => 'updated tips',
            'sent_tips' => 'updated sent tips',
            'file_id' => 46,
            'elapsed_months' => 4,
            'tip' => 'updated tip',
            'tip_detail' => 'updated detailed tip'
        ]);

        // Mock the executeQuery method to return success
        $this->databaseServiceMock->expects($this->once())
            ->method('executeQuery')
            ->with($this->anything(), $this->anything())
            ->willReturn(true);  // Simulate successful update

        // Act: Call the update method
        $result = $this->downloadModel->update();

        // Assert: Verify that the update was called successfully
        $this->assertTrue($result);
    }
}

