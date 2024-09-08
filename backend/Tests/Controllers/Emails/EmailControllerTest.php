<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Emails\EmailController;
use App\Models\Emails\EmailModel;

class EmailControllerTest extends TestCase
{
    private $emailModelMock;
    private $emailController;

    protected function setUp(): void
    {
        // Mock EmailModel
        $this->emailModelMock = $this->createMock(EmailModel::class);

        // Inject the mock EmailModel into the EmailController
        $this->emailController = new EmailController($this->emailModelMock);
    }

    public function testGetEmailBySeriesAndSequence()
    {
        // Arrange: Define the expected result
        $expectedEmailData = [
            'id' => 1, 
            'subject' => 'Test Email', 
            'body' => 'Test Body',
            'plain_text_only' => false,
            'headers' => '',
            'template' => '',
            'series' => 'test-series',
            'sequence' => 1,
            'params' => ''
        ];

        // Mock the findOneInSeries method on the EmailModel to return the expected data
        $this->emailModelMock->method('findOneInSeries')
                             ->willReturn($expectedEmailData);

        // Act: Call getEmailBySeriesAndSequence on the controller
        $result = $this->emailController->getEmailBySeriesAndSequence('test-series', 1);

        // Assert: Check that the result matches the expected data
        $this->assertEquals($expectedEmailData, $result);
    }

    public function testGetEmailBySeriesAndSequenceReturnsBlankRecord()
    {
        // Arrange: Define the expected result when no email is found
        $expectedBlankRecord = [
            'id' => null,
            'subject' => 'What is the subject?',
            'body' => 'Edit this email',
            'plain_text_only' => false,
            'headers' => '',
            'template' => '',
            'series' => 'test-series',
            'sequence' => 1,
            'params' => ''
        ];

        // Mock the findOneInSeries method to return null (no email found)
        $this->emailModelMock->method('findOneInSeries')
                             ->willReturn(null);

        // Act: Call getEmailBySeriesAndSequence on the controller
        $result = $this->emailController->getEmailBySeriesAndSequence('test-series', 1);

        // Assert: Check that the result matches the expected blank record
        $this->assertEquals($expectedBlankRecord, $result);
    }
}
