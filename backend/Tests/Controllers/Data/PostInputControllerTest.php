<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Data\PostInputController;
use App\Services\Security\SanitizeInputService;

class PostInputControllerTest extends TestCase
{
    private $sanitizeInputServiceMock;
    private $postInputController;

    protected function setUp(): void
    {
        // Mock SanitizeInputService
        $this->sanitizeInputServiceMock = $this->createMock(SanitizeInputService::class);

        // Mock the PHP input stream for JSON requests
        $this->phpInputMock = $this->getMockBuilder('stdClass')
                                   ->setMethods(['file_get_contents'])
                                   ->getMock();
    }

    public function testHandleJsonRequest()
    {
        // Arrange
        $jsonData = json_encode(['data' => ['field1' => 'value1', 'apiKey' => '12345']]);
        $sanitizedData = ['field1' => 'sanitized_value1'];

        // Set up JSON request header
        $_SERVER['CONTENT_TYPE'] = 'application/json';

        // Mock file_get_contents to return the JSON data
        $this->phpInputMock->method('file_get_contents')
                           ->willReturn($jsonData);

        // Mock the sanitize method to return sanitized data
        $this->sanitizeInputServiceMock->method('sanitize')
                                       ->willReturn($sanitizedData);

        // Act
        $this->postInputController = new PostInputController($this->sanitizeInputServiceMock);
        $result = $this->postInputController->getDataSet();

        // Assert
        $this->assertEquals($sanitizedData, $result);
    }

    public function testHandleFormRequest()
    {
        // Arrange
        $_POST['formData'] = [
            ['name' => 'field1', 'value' => 'value1'],
            ['name' => 'apiKey', 'value' => '12345']
        ];
        $sanitizedData = ['field1' => 'sanitized_value1'];

        // Mock the sanitize method to return sanitized data
        $this->sanitizeInputServiceMock->method('sanitize')
                                       ->willReturn($sanitizedData);

        // Act
        $this->postInputController = new PostInputController($this->sanitizeInputServiceMock);
        $result = $this->postInputController->getDataSet();

        // Assert
        $this->assertEquals($sanitizedData, $result);
    }

    public function testHandleNoFormData()
    {
        // Arrange
        $_POST = [];

        // Act
        $this->postInputController = new PostInputController($this->sanitizeInputServiceMock);
        $result = $this->postInputController->getDataSet();

        // Assert
        $this->assertEquals([], $result); // Should return empty array if no form data
    }

    public function testGetApiKey()
    {
        // Arrange
        $jsonData = json_encode(['data' => ['field1' => 'value1', 'apiKey' => '12345']]);
        $sanitizedData = ['field1' => 'value1', 'apiKey' => '12345'];

        // Set up JSON request header
        $_SERVER['CONTENT_TYPE'] = 'application/json';

        // Mock file_get_contents to return the JSON data
        $this->phpInputMock->method('file_get_contents')
                           ->willReturn($jsonData);

        // Mock the sanitize method to return sanitized data
        $this->sanitizeInputServiceMock->method('sanitize')
                                       ->willReturn($sanitizedData);

        // Act
        $this->postInputController = new PostInputController($this->sanitizeInputServiceMock);
        $apiKey = $this->postInputController->getApiKey();

        // Assert
        $this->assertEquals('12345', $apiKey);
    }

    public function testGetApiKeyWhenMissing()
    {
        // Arrange
        $jsonData = json_encode(['data' => ['field1' => 'value1']]);
        $sanitizedData = ['field1' => 'value1'];

        // Set up JSON request header
        $_SERVER['CONTENT_TYPE'] = 'application/json';

        // Mock file_get_contents to return the JSON data
        $this->phpInputMock->method('file_get_contents')
                           ->willReturn($jsonData);

        // Mock the sanitize method to return sanitized data
        $this->sanitizeInputServiceMock->method('sanitize')
                                       ->willReturn($sanitizedData);

        // Act
        $this->postInputController = new PostInputController($this->sanitizeInputServiceMock);
        $apiKey = $this->postInputController->getApiKey();

        // Assert
        $this->assertNull($apiKey); // API key should be null if not present
    }
}
