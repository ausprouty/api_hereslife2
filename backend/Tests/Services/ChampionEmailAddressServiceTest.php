<?php

use PHPUnit\Framework\TestCase;
use App\Services\ChampionEmailAddressService;
use App\Services\DatabaseService;
use PDOStatement;
use PDO;

class ChampionEmailAddressServiceTest extends TestCase
{
    protected $databaseServiceMock;
    protected $service;

    /**
     * Set up the mocks and the service instance before each test.
     */
    protected function setUp(): void
    {
        // Mock the DatabaseService
        $this->databaseServiceMock = $this->createMock(DatabaseService::class);

        // Inject the mocked DatabaseService into the ChampionEmailAddressService
        $this->service = new ChampionEmailAddressService($this->databaseServiceMock);
    }

    /**
     * Test getChampionEmails with code 'australia'.
     */
    public function testGetChampionEmailsWithCodeAustralia()
    {
        $code = 'australia';
        $expectedQuery = "SELECT cid, email, first_name, country FROM hl_champions 
                    WHERE country = :country
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
        $expectedParams = [
            ':country' => 'Australia',
            ':blank' => '',
            ':point' => '!%'
        ];

        // Mock the expected result
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                ['cid' => 1, 'email' => 'australia@example.com', 'first_name' => 'John', 'country' => 'Australia']
            ]);

        // Mock the executeQuery method in DatabaseService
        $this->databaseServiceMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with($expectedQuery, $expectedParams)
            ->willReturn($statementMock);

        // Call the method and check the result
        $result = $this->service->getChampionEmails($code);
        $this->assertCount(1, $result);
        $this->assertEquals('australia@example.com', $result[0]['email']);
    }

    // You can write similar tests for other cases (e.g., 'test', 'australia_not_power_to_change', etc.)
}
