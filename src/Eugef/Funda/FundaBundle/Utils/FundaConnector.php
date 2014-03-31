<?php
namespace Eugef\Funda\FundaBundle\Utils;

use Lsw\ApiCallerBundle\Call\HttpGetJson;

/**
 * Contains methods to connect with Funda API
 */
class FundaConnector
{
    const API_URI = 'http://partnerapi.funda.nl/feeds/Aanbod.svc/json'; 
    const OBJECTS_PER_PAGE = 25;
    
    /**
     * Funda API key
     * @var string 
     */
    private $apiKey;
    
    private $apiCaller;
    
    public function __construct($apiKey, $apiCaller)
    {
        $this->apiKey = $apiKey;
        $this->apiCaller = $apiCaller;
    }
    
    /**
     * Perform HTTP request to Funda search API
     * 
     * @param array $searchCriteria Object search criteria
     * @return object JSON-decoded response
     */
    private function searchObjects($searchCriteria) {
        return $this->apiCaller->call(new HttpGetJson(self::API_URI . '/' . $this->apiKey, $searchCriteria));
    }
    
    /**
     * Collect agencies statistics data from Funda object search
     * 
     * @param array $searchCriteria Objects search criteria
     * @param int $pageCount How much pages to iterate
     * @return array
     */
    public function getAgenciesData($searchCriteria, $pageCount = 1) {
        $result = array();

        for ($i=1; $i<=$pageCount; $i++) {
            /* make request per each page */
            $response  = $this->searchObjects(array_merge(
                $searchCriteria, 
                array(
                    'page' => $i,
                    'pagesize' => self::OBJECTS_PER_PAGE,
                )
            ));
        
            /* gather agency statistics from the page */
            foreach ($response->Objects as $object) {
                if (isset($result[$object->MakelaarId])) {
                    $result[$object->MakelaarId]['count']++;
                }
                else {
                    $result[$object->MakelaarId] = array(
                        'name' => $object->MakelaarNaam,
                        'count' => 1,
                    );
                }
            }
        }
        
        return $result;
    }    
    
}
