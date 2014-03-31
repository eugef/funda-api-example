<?php

namespace Eugef\Funda\FundaBundle\Entity;

use Eugef\Funda\FundaBundle\Entity\AgencyReport;

/**
 * AgencyReport manager
 * 
 * Store and retrive report data from the DB
 */
class AgencyReportManager
{
    private $em;
    
    public function __construct($entityManager) {
        $this->em = $entityManager;
    }
    
    /**
     * Save results for specified report
     * 
     * Existing report data is ovewritten by new values
     * 
     * @param int $reportId 
     * @param array $reportData
     * @return void 
     */
    public function save($reportId, $reportData) {
        // Delete existing report results
        $connection = $this->em->getConnection();
        $statement = $connection->prepare('DELETE FROM agency_report WHERE report = :report');
        $statement->execute(array('report' => $reportId));

        
        // Insert new report results
        foreach ($reportData as $agencyId => $agencyData) {
            $reportData = new AgencyReport();
            $reportData
                ->setReport($reportId)
                ->setAgencyId($agencyId)
                ->setAgencyName($agencyData['name'])
                ->setAmount($agencyData['count']);
            
            $this->em->persist($reportData);
        }
        $this->em->flush();
    }
    
    /**
     * Return top agencies for specified report
     * 
     * @param int $reportId
     * @return array
     */
    public function getTop($reportId) {
        $connection = $this->em->getConnection();
        $statement = $connection->prepare('SELECT * FROM agency_report WHERE report = :report ORDER BY amount DESC LIMIT 0, 10');
        $statement->execute(array(
            'report' => $reportId
        ));
        
        return $statement->fetchAll();
    }
}
