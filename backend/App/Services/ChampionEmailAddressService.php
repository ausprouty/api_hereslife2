<?php

Namespace App\Services;

use App\Services\DatabaseService;
use PDO;

class ChampionEmailAddressService {
    private $databaseService;

    public function __construct(DatabaseService $databaseService) {
        $this->databaseService = $databaseService;
    }

    public function getChampionEmails($code) {

        // Define the query and parameters based on the code
        switch ($code) {
            case 'test':
                $query = "SELECT cid, email, first_name, country FROM hl_champions
                    WHERE cid = :cid 
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':cid' => 1,
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
            case 'australia':
                $query = "SELECT cid, email, first_name, country FROM hl_champions 
                    WHERE country = :country
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'Australia',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;

            case 'australia_not_power_to_change':
                $query = "SELECT cid, email, first_name, country FROM hl_champions 
                    WHERE country = :country
                    AND organization NOT LIKE :organization
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point ";
                $params = [
                    ':country' => 'Australia',
                    ':organization' => '%Power to Change%',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
                
            case 'australia_nsw':
                    $query = "SELECT cid, email, first_name, country FROM hl_champions WHERE country = :country AND state = :state
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'Australia',
                    ':state' => 'NSW',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
                
            case 'australia_nt':
                    $query = "SELECT cid, email, first_name, country FROM hl_champions WHERE country = :country AND state = :state
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'Australia',
                    ':state' => 'NT',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
                
            case 'australia_qld':
                $query = "SELECT cid, email, first_name, country FROM hl_champions
                    WHERE country = :country AND state = :state
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'Australia',
                    ':state' => 'QLD',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
                
            case 'australia_sa':
                $query = "SELECT cid, email, first_name, country FROM hl_champions
                    WHERE country = :country AND state = :state
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'Australia',
                    ':state' => 'SA',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
                
            case 'australia_vic':
                $query = "SELECT cid, email, first_name, country FROM hl_champions
                    WHERE country = :country AND state = :state
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'Australia',
                    ':state' => 'VIC',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
                
            case 'canada':
                $query = "SELECT cid, email, first_name, country FROM hl_champions
                    WHERE country = :country
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'Canada',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
                
            case 'french_speaking_countries':
                $french = array(
                    'Belgium',
                    'Benin',
                    'Burkina',
                    'Burundi',
                    'Cameroon',
                    'Canada',
                    'Central African Republic',
                    'Chad',
                    'Comoros',
                    'Congo',
                    'Congo, Democratic Republic of',
                    'Cote d Ivoire',
                    'Djibouti',
                    'France',
                    'Gabon',
                    'Guinea',
                    'Haiti',
                    'Luxembourg',
                    'Madagascar',
                    'Mali',
                    'Monaco',
                    'Niger',
                    'Rwanda',
                    'Senegal',
                    'Seychelles',
                    'Switzerland',
                    'Togo',
                    'Vanuatu',
                    'Vietnam'
                );
                
                // Build the query with named placeholders
                $placeholders = [];
                $params = [
                    ':blank' => '',
                    ':point' => '!%',
                ];
                
                foreach ($french as $index => $country) {
                    $placeholder = ':country' . $index;
                    $placeholders[] = $placeholder;
                    $params[$placeholder] = $country;
                }
                
                $query = "SELECT cid, email, first_name, country FROM hl_champions
                          WHERE email != :blank
                          AND email IS NOT NULL
                          AND email NOT LIKE :point
                          AND country IN (" . implode(", ", $placeholders) . ")";
                
                // Now $params contains all named parameters, and they are bound to the corresponding placeholders
                
                break;
                
            
                
            case 'indian':
        // Query to get the distinct champion details directly
                $query = '
                    SELECT DISTINCT c.cid, c.email, c.first_name, c.country
                    FROM hl_champions AS c
                    INNER JOIN hl_downloads AS d ON c.cid = d.champion_id
                    WHERE (
                        d.file_name LIKE :f1 OR
                        d.file_name LIKE :f2 OR
                        d.file_name LIKE :f3 OR
                        d.file_name LIKE :f4 OR
                        d.file_name LIKE :f5 OR
                        d.file_name LIKE :f6
                    )
                    AND c.email != :blank
                    AND c.email IS NOT NULL
                    AND c.email NOT LIKE :point
                ';

                // Parameters for the query
                $params = array(
                    ':f1' => '%Hnd%',
                    ':f2' => '%Gjr%',
                    ':f3' => '%Tam%',
                    ':f4' => '%Bng%',
                    ':f5' => '%Nep%',
                    ':f6' => '%Sin%',
                    ':blank' => '',
                    ':point' => '!%'
                );
                break;
                
            case 'lunar_new_year':
                $query = '
                    SELECT DISTINCT c.cid, c.email, c.first_name, c.country
                    FROM hl_champions AS c
                    INNER JOIN hl_downloads AS d ON c.cid = d.champion_id
                    WHERE (
                        d.file_name LIKE :f1 OR
                        d.file_name LIKE :f2 OR
                        d.file_name LIKE :f3 OR
                        d.file_name LIKE :f4 OR
                        d.file_name LIKE :f5 
                    )
                    AND c.email != :blank
                    AND c.email IS NOT NULL
                    AND c.email NOT LIKE :point
                ';
                // Parameters for the query
                $params = array(
                    ':f1' => '%Kkn%',
                    ':f2' => '%Chn%',
                    ':f3' => '%Cht%',
                    ':f4' => '%Tag%',
                    ':f5' => '%Vie%',
                    ':blank' => '',
                    ':point' => '!%'
                );
                break;
            
                
            case 'muslim':
                $query = '
                    SELECT DISTINCT c.cid, c.email, c.first_name, c.country
                    FROM hl_champions AS c
                    INNER JOIN hl_downloads AS d ON c.cid = d.champion_id
                    WHERE (
                        d.file_name LIKE :f1 OR
                        d.file_name LIKE :f2 
                    )
                    AND c.email != :blank
                    AND c.email IS NOT NULL
                    AND c.email NOT LIKE :point
                ';
                // Parameters for the query
                $params = array(
                    ':f1' => '%Mp%',
                    ':f2' => '%Mb%',
                    ':blank' => '',
                    ':point' => '!%'
                );
                break;
                
            case 'non_muslim':
                $query = '
                    SELECT DISTINCT c.cid, c.email, c.first_name, c.country
                    FROM hl_champions AS c
                    LEFT JOIN hl_downloads AS d ON c.cid = d.champion_id
                    AND (d.file_name LIKE :f1 OR d.file_name LIKE :f2)
                    WHERE d.champion_id IS NULL
                    AND c.email != :blank
                    AND c.email IS NOT NULL
                    AND c.email NOT LIKE :point
                ';
                // Parameters for the query
                $params = array(
                    ':f1' => '%Mp%',
                    ':f2' => '%Mb%',
                    ':blank' => '',
                    ':point' => '!%'
                );
                break;
                
            case 'not_australia':
                $query = "SELECT cid, email, first_name, country FROM hl_champions 
                    WHERE country != :country
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'Australia',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
            

            case 'not_usa':
                $query = "SELECT cid, email, first_name, country FROM hl_champions 
                    WHERE country != :country
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'United States',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
                
            case 'vietnamese':
                case 'lunar_new_year':
                    $query = '
                        SELECT DISTINCT c.cid, c.email, c.first_name, c.country
                        FROM hl_champions AS c
                        INNER JOIN hl_downloads AS d ON c.cid = d.champion_id
                        WHERE d.file_name LIKE :f1 OR
                        AND c.email != :blank
                        AND c.email IS NOT NULL
                        AND c.email NOT LIKE :point
                    ';
                    // Parameters for the query
                    $params = array(
                        ':f1' => '%Vie%',
                        ':blank' => '',
                        ':point' => '!%'
                    );
                    break;
                
            case 'usa':
                $query = "SELECT cid, email, first_name, country FROM hl_champions 
                    WHERE country = :country
                    AND email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':country' => 'United States',
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
                
            default:
                $query = "SELECT cid, email, first_name, country FROM hl_champions
                    WHERE email != :blank
                    AND email IS NOT NULL
                    AND email NOT LIKE :point";
                $params = [
                    ':blank' => '',
                    ':point' => '!%'
                ];
                break;
        }
        // Execute the query using DatabaseService
        $results = $this->databaseService->executeQuery($query, $params);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}

