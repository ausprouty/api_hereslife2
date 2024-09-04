<?php

use PHPUnit\Framework\TestCase;
use App\Models\Emails\EmailQueModel;
use App\Services\DatabaseService;

class EmailQueModelTest extends TestCase
{
    private $databaseServiceMock;
    private $emailQueModel;

    protected function setUp(): void
    {
        // Mock DatabaseService
        $this->databaseServiceMock = $this->createMock(DatabaseService::class);

        // Inject the mock DatabaseService into EmailQueModel
        $this->emailQueModel = new EmailQueModel($this->databaseServiceMock, [
            'delay_until' => time(),
            'email_from' => 'from@example.com',
            'email_to' => 'to@example.com',
            'email_id' => 1,
            'champion_id' => 1,
            'subject' => 'Test Subject',
            'body' => 'Test Body',
            'plain_text_only' => 1,
            'headers' => '',
            'plain_text_body' => 'Test Plain Text Body',
            'template' => 'default',
            'params' => '{}',
        ]);
    }

    public function testSave()
    {
        // Mock the executeUpdate method to return success
        $this->databaseServiceMock->expects($this->once())
            ->method('executeUpdate')
            ->with($this->anything(), $this->anything())
            ->willReturn(1);  // Simulate a successful insert

        // Act: Call save method
        $result = $this->emailQueModel->save();

        // Assert: Ensure save was successful
        $this->assertEquals(1, $result);
    }

    public function testUpdate()
    {
        // Arrange: Set ID for the update operation
        $this->emailQueModel->create([
            'id' => 1,
            'delay_until' => time(),
            'email_from' => 'from@example.com',
            'email_to' => 'to@example.com',
            'email_id' => 1,
            'champion_id' => 1,
            'subject' => 'Updated Subject',
            'body' => 'Updated Body',
            'plain_text_only' => 1,
            'headers' => '',
            'plain_text_body' => 'Updated Plain Text Body',
            'template' => 'updated-template',
            'params' => '{}',
        ]);

        // Mock the executeUpdate method to return success
        $this->databaseServiceMock->expects($this->once())
            ->method('executeUpdate')
            ->with($this->anything(), $this->anything())
            ->willReturn(1);  // Simulate a successful update

        // Act: Call update method
        $result = $this->emailQueModel->update();

        // Assert: Ensure update was successful
        $this->assertEquals(1, $result);
    }

    public function testDelete()
    {
        // Arrange: Set ID for the delete operation
        $this->emailQueModel->create([
            'id' => 1
        ]);

        // Mock the executeUpdate method for delete
        $this->databaseServiceMock->expects($this->once())
            ->method('executeUpdate')
            ->with($this->anything(), [':id' => 1])
            ->willReturn(1);  // Simulate a successful delete

        // Act: Call delete method
        $result = $this->emailQueModel->delete();

        // Assert: Ensure delete was successful
        $this->assertEquals(1, $result);
    }

    public function testQueEmails()
    {
        // Arrange: Prepare a list of champions and mock save operations
        $champions = [
            ['cid' => 1],
            ['cid' => 2],
            ['cid' => 3]
        ];
        $letterId = 1;

        // Mock the save method to simulate multiple saves
        $this->databaseServiceMock->expects($this->exactly(3))
            ->method('executeUpdate')
            ->with($this->anything(), $this->anything())
            ->willReturn(1);  // Simulate successful saves for each champion

        // Act: Call queEmails
        $result = $this->emailQueModel->queEmails($champions, $letterId);

        // Assert: Check that the correct number of emails were queued
        $this->assertEquals('3 emails qued', $result);
    }
}
