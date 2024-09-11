<?php

namespace App\Services\Emails;

use App\Services\Database\DatabaseService;
use PDO;

/**
 * ChampionEmailAddressService
 *
 * Service responsible for retrieving email addresses of champions based on various criteria.
 * Queries are built dynamically based on a provided code.
 */
class ChampionEmailAddressService {
    private $databaseService;

    /**
     * Constructor method to inject DatabaseService.
     *
     * @param DatabaseService $databaseService
     */
    public function __construct(DatabaseService $databaseService) {
        $this->databaseService = $databaseService;
    }

    /**
     * Retrieves champion emails based on the provided code.
     *
     * @param string $code Criteria code to filter champion emails.
     * @param string|null $state Optional state filter for Australian regions.
     * @return array Returns an array of champion emails and details.
     */
    public function getChampionEmails($code, $state = null): array {
        $queryData = $this->buildQuery($code, $state);

        // Execute the query using DatabaseService
        $results = $this->databaseService->executeQuery($queryData['query'], $queryData['params']);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Builds a query and parameters based on the provided code and optional state.
     * Extracts logic for better maintainability.
     *
     * @param string $code Query code for specific logic.
     * @param string|null $state Optional state filter.
     * @return array An associative array containing 'query' and 'params'.
     */
    private function buildQuery(string $code, ?string $state = null): array {
        $params = [
            ':blank' => '',
            ':point' => '!%'
        ];

        switch ($code) {
            case 'australia':
                // Check if a specific state filter is provided
                if ($state) {
                    $query = $this->getAustraliaStateQuery();
                    $params[':country'] = 'Australia';
                    $params[':state'] = $state;
                } else {
                    $query = $this->getCountryQuery();
                    $params[':country'] = 'Australia';
                }
                break;

            case 'non_australia':
                $query = $this->getNonCountryQuery();
                $params[':country'] = 'Australia';
                break;

            case 'usa':
                $query = $this->getCountryQuery();
                $params[':country'] = 'USA';
                break;

            case 'non_usa':
                $query = $this->getNonCountryQuery();
                $params[':country'] = 'USA';
                break;

            case 'australia_not_power_to_change':
                $query = $this->getCountryOrgExclusionQuery();
                $params[':country'] = 'Australia';
                $params[':organization'] = '%Power to Change%';
                break;

            case 'french_speaking_countries':
                $query = $this->getFrenchSpeakingCountriesQuery($params);
                break;

            case 'muslim':
                $query = $this->getMuslimQuery();
                break;

            case 'non_muslim':
                $query = $this->getNonMuslimQuery();
                $params[':f1'] = '%Mp%';
                $params[':f2'] = '%Mb%';
                break;

            default:
                $query = $this->getDefaultQuery();
                break;
        }

        return ['query' => $query, 'params' => $params];
    }

    /**
     * Builds a query for champions from a specific country.
     *
     * @return string The SQL query string.
     */
    private function getCountryQuery(): string {
        return "SELECT cid, email, first_name, country FROM hl_champions 
                WHERE country = :country
                AND email != :blank
                AND email IS NOT NULL
                AND email NOT LIKE :point";
    }

    /**
     * Builds a query for champions who are not from a specific country.
     *
     * @return string The SQL query string.
     */
    private function getNonCountryQuery(): string {
        return "SELECT cid, email, first_name, country FROM hl_champions 
                WHERE country != :country
                AND email != :blank
                AND email IS NOT NULL
                AND email NOT LIKE :point";
    }

    /**
     * Builds a query for champions based on country and state within Australia.
     *
     * @return string The SQL query string.
     */
    private function getAustraliaStateQuery(): string {
        return "SELECT cid, email, first_name, country, state FROM hl_champions 
                WHERE country = :country
                AND state = :state
                AND email != :blank
                AND email IS NOT NULL
                AND email NOT LIKE :point";
    }

    /**
     * Builds a query for champions excluding a specific organization.
     *
     * @return string The SQL query string.
     */
    private function getCountryOrgExclusionQuery(): string {
        return "SELECT cid, email, first_name, country FROM hl_champions 
                WHERE country = :country
                AND organization NOT LIKE :organization
                AND email != :blank
                AND email IS NOT NULL
                AND email NOT LIKE :point";
    }

    /**
     * Builds a query for French-speaking countries.
     * Dynamically constructs the IN clause based on the list of countries.
     *
     * @param array &$params Reference to the query parameters array to add country values.
     * @return string The SQL query string.
     */
    private function getFrenchSpeakingCountriesQuery(array &$params): string {
        $french = [
            'Belgium', 'Benin', 'Burkina', 'Burundi', 'Cameroon',
            'Canada', 'Central African Republic', 'Chad', 'Comoros',
            'Congo', 'Congo, Democratic Republic of', 'Cote d Ivoire',
            'Djibouti', 'France', 'Gabon', 'Guinea', 'Haiti',
            'Luxembourg', 'Madagascar', 'Mali', 'Monaco', 'Niger',
            'Rwanda', 'Senegal', 'Seychelles', 'Switzerland', 'Togo',
            'Vanuatu', 'Vietnam'
        ];

        $placeholders = [];
        foreach ($french as $index => $country) {
            $placeholder = ':country' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = $country;
        }

        return "SELECT cid, email, first_name, country FROM hl_champions
                WHERE email != :blank
                AND email IS NOT NULL
                AND email NOT LIKE :point
                AND country IN (" . implode(", ", $placeholders) . ")";
    }

    /**
     * Builds a query for Muslim champions.
     *
     * @return string The SQL query string.
     */
    private function getMuslimQuery(): string {
        return 'SELECT DISTINCT c.cid, c.email, c.first_name, c.country
                FROM hl_champions AS c
                INNER JOIN hl_downloads AS d ON c.cid = d.champion_id
                WHERE (d.file_name LIKE :f1 OR d.file_name LIKE :f2)
                AND c.email != :blank
                AND c.email IS NOT NULL
                AND c.email NOT LIKE :point';
    }

    /**
     * Builds a query for non-Muslim champions (champions who have not downloaded certain files).
     *
     * @return string The SQL query string.
     */
    private function getNonMuslimQuery(): string {
        return "SELECT DISTINCT c.cid, c.email, c.first_name, c.country
                FROM hl_champions AS c
                LEFT JOIN hl_downloads AS d ON c.cid = d.champion_id
                AND (d.file_name LIKE :f1 OR d.file_name LIKE :f2)
                WHERE d.champion_id IS NULL
                AND c.email != :blank
                AND c.email IS NOT NULL
                AND c.email NOT LIKE :point";
    }

    /**
     * Builds a default query for retrieving champions with emails.
     *
     * @return string The SQL query string.
     */
    private function getDefaultQuery(): string {
        return "SELECT cid, email, first_name, country FROM hl_champions
                WHERE email != :blank
                AND email IS NOT NULL
                AND email NOT LIKE :point";
    }
}
