<?php

use PHPUnit\Framework\TestCase;
use App\Services\ChampionEmailAddressService;
use App\Services\Database\DatabaseService;
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
     * Test getChampionEmails with code 'australia_not_power_to_change'.
     * Ensures filtering by organization and country works.
     */
    public function testGetChampionEmailsWithCodeAustraliaNotPowerToChange()
    {
        $code = 'australia_not_power_to_change';
        $expectedQueryPart = "SELECT cid, email, first_name, country FROM hl_champions 
                    WHERE country = :country
                    AND organization NOT LIKE :organization
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";

        $expectedParams = [
            ':country' => 'Australia',
            ':organization' => '%Power to Change%',
            ':blank' => '',
            ':point' => '!%'
        ];

        // Mock the expected result
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                ['cid' => 1, 'email' => 'example@example.com', 'first_name' => 'John', 'country' => 'Australia']
            ]);

        // Mock the executeQuery method in DatabaseService
        $this->databaseServiceMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with($this->callback(function ($query) use ($expectedQueryPart) {
                // Check that the important parts of the query are present
                return strpos($query, 'SELECT cid, email, first_name, country FROM hl_champions') !== false
                    && strpos($query, 'WHERE country = :country') !== false
                    && strpos($query, 'AND organization NOT LIKE :organization') !== false
                    && strpos($query, 'AND email != :blank') !== false
                    && strpos($query, 'AND email IS NOT NULL') !== false
                    && strpos($query, 'AND email NOT LIKE :point') !== false;
            }), $expectedParams)
            ->willReturn($statementMock);

        // Call the method and check the result
        $result = $this->service->getChampionEmails($code);
        $this->assertCount(1, $result);
        $this->assertEquals('example@example.com', $result[0]['email']);
    }

    /**
     * Test getChampionEmails with code 'french_speaking_countries'.
     * Ensures filtering by a large set of countries works.
     */
    public function testGetChampionEmailsWithCodeFrenchSpeakingCountries()
    {
        $code = 'french_speaking_countries';

        // Generate expected query dynamically based on placeholders
        $frenchCountries = [
            'Belgium', 'Benin', 'Burkina', 'Burundi', 'Cameroon', 'Canada',
            'Central African Republic', 'Chad', 'Comoros', 'Congo', 'Congo, Democratic Republic of',
            'Cote d Ivoire', 'Djibouti', 'France', 'Gabon', 'Guinea', 'Haiti', 'Luxembourg',
            'Madagascar', 'Mali', 'Monaco', 'Niger', 'Rwanda', 'Senegal', 'Seychelles', 
            'Switzerland', 'Togo', 'Vanuatu', 'Vietnam'
        ];

        $placeholders = [];
        $params = [
            ':blank' => '',
            ':point' => '!%'
        ];

        foreach ($frenchCountries as $index => $country) {
            $placeholder = ':country' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = $country;
        }

        $expectedQueryPart = "SELECT cid, email, first_name, country FROM hl_champions
                    WHERE email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point
                    AND country IN (:country0, :country1, :country2, :country3, :country4, :country5, :country6, :country7, :country8, :country9, :country10, :country11, :country12, :country13, :country14, :country15, :country16, :country17, :country18, :country19, :country20, :country21, :country22, :country23, :country24, :country25, :country26, :country27, :country28)";
        
        // Mock the expected result
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                ['cid' => 1, 'email' => 'france@example.com', 'first_name' => 'Jean', 'country' => 'France']
            ]);

        // Mock the executeQuery method in DatabaseService
        $this->databaseServiceMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with($this->callback(function ($query) use ($expectedQueryPart) {
                // Check that the important parts of the query are present
                return strpos($query, 'SELECT cid, email, first_name, country FROM hl_champions') !== false
                    && strpos($query, 'AND country IN') !== false;
            }), $params)
            ->willReturn($statementMock);

        // Call the method and check the result
        $result = $this->service->getChampionEmails($code);
        $this->assertCount(1, $result);
        $this->assertEquals('france@example.com', $result[0]['email']);
    }

   /**
 * Test getChampionEmails with code 'non_muslim'.
 * Ensures left join logic works and returns champions without matching downloads.
 */
    public function testGetChampionEmailsWithCodeNonMuslim()
    {
        $code = 'non_muslim';

        // Define the expected query (collapsed into a single line for easier comparison)
        $expectedQueryPart = 
            "SELECT DISTINCT c.cid, c.email, c.first_name, c.country
            FROM hl_champions AS c
            LEFT JOIN hl_downloads AS d ON c.cid = d.champion_id
            AND (d.file_name LIKE :f1 OR d.file_name LIKE :f2)
            WHERE d.champion_id IS NULL
            AND c.email != :blank
            AND c.email IS NOT NULL
            AND c.email NOT LIKE :point";
        
        // Define expected parameters
        $expectedParams = [
            ':f1' => '%Mp%',
            ':f2' => '%Mb%',
            ':blank' => '',
            ':point' => '!%'
        ];

        // Mock the expected result from the database
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                ['cid' => 1, 'email' => 'nonmuslim@example.com', 'first_name' => 'Ali', 'country' => 'Country X']
            ]);

        // Mock the executeQuery method in DatabaseService
        $this->databaseServiceMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with($this->callback(function ($actualQuery) use ($expectedQueryPart) {
                // Normalize both queries by removing extra whitespace and newlines
                $normalizedExpected = preg_replace('/\s+/', ' ', trim($expectedQueryPart));
                $normalizedActual = preg_replace('/\s+/', ' ', trim($actualQuery));

                // Compare the normalized queries
                return $normalizedExpected === $normalizedActual;
            }), $expectedParams)
            ->willReturn($statementMock);

        // Call the method and verify the result
        $result = $this->service->getChampionEmails($code);
        $this->assertCount(1, $result);
        $this->assertEquals('nonmuslim@example.com', $result[0]['email']);
    }

}
