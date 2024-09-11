<?php

use PHPUnit\Framework\TestCase;
use App\Models\Emails\EmailQueModel;
use App\Services\Database\DatabaseService;
use \PDO;

class EmailQueModelTest extends TestCase {

    private $databaseServiceMock;
    private $emailQueModel;

    protected function setUp(): void {
        // Mock the DatabaseService
        $this->databaseServiceMock = $this->createMock(DatabaseService::class);

        // Instantiate the EmailQueModel with the mocked DatabaseService
        $this->emailQueModel = new EmailQueModel($this->databaseServiceMock);
    }

    public function testCreate() {
        // Set values to the model
        $params = [
            'delay_until' => 1622505600,
            'email_from' => 'test@example.com',
            'email_to' => 'recipient@example.com',
            'email_id' => 1,
            'champion_id' => 2,
            'subject' => 'Test Subject',
            'body' => 'This is a test body.',
            'plain_text_only' => 0,
            'headers' => 'Header: Value',
            'plain_text_body' => 'Plain text body.',
            'template' => 'template_name',
            'params' => 'param=value',
        ];

        $this->emailQueModel->setValues($params);

        // Mock the executeUpdate and getLastInsertId methods
        $this->databaseServiceMock
            ->expects($this->once())
            ->method('executeUpdate')
            ->with($this->callback(function ($query) {
                return strpos($query, 'INSERT INTO hl_email_que') !== false;
            }), $this->isType('array'));

        $this->databaseServiceMock
            ->expects($this->once())
            ->method('getLastInsertId')
            ->willReturn('1');

        // Call create method and assert the result
        $insertedId = $this->emailQueModel->create();
        $this->assertEquals(1, $insertedId);
    }

    public function testUpdate() {
        // Set values to the model, including an ID
        $params = [
            'id' => 1,
            'delay_until' => 1622505600,
            'email_from' => 'test@example.com',
            'email_to' => 'recipient@example.com',
            'email_id' => 1,
            'champion_id' => 2,
            'subject' => 'Test Subject',
            'body' => 'This is a test body.',
            'plain_text_only' => 0,
            'headers' => 'Header: Value',
            'plain_text_body' => 'Plain text body.',
            'template' => 'template_name',
            'params' => 'param=value',
        ];

        $this->emailQueModel->setValues($params);

        // Mock the executeUpdate method
        $this->databaseServiceMock
            ->expects($this->once())
            ->method('executeUpdate')
            ->with($this->callback(function ($query) {
                return strpos($query, 'UPDATE hl_email_que') !== false;
            }), $this->isType('array'))
            ->willReturn(true);

        // Call update method and assert the result
        $result = $this->emailQueModel->update();
        $this->assertTrue($result);
    }

    public function testRead() {
        // Mock the executeQuery method and its return value
        $this->databaseServiceMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with($this->callback(function ($query) {
                return strpos($query, 'SELECT * FROM hl_email_que WHERE id = :id') !== false;
            }), $this->isType('array'))
            ->willReturn($this->createConfiguredMock(PDOStatement::class, [
                'fetch' => [
                    'id' => 1,
                    'email_from' => 'test@example.com',
                    'email_to' => 'recipient@example.com',
                    'subject' => 'Test Subject',
                    'body' => 'This is a test body.'
                ]
            ]));

        // Call read method and assert the result
        $result = $this->emailQueModel->read(1);
        $this->assertIsArray($result);
        $this->assertEquals('test@example.com', $result['email_from']);
        $this->assertEquals('recipient@example.com', $result['email_to']);
    }

    public function testDelete() {
        // Mock the executeUpdate method for deletion
        $this->databaseServiceMock
            ->expects($this->once())
            ->method('executeUpdate')
            ->with($this->callback(function ($query) {
                return strpos($query, 'DELETE FROM hl_email_que WHERE id = :id') !== false;
            }), $this->isType('array'))
            ->willReturn(true);

        // Call delete method and assert the result
        $result = $this->emailQueModel->delete(1);
        $this->assertTrue($result);
    }
}
