<?php

namespace Eugef\Funda\FundaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Eugef\Funda\FundaBundle\Utils\FundaConnector;
use Eugef\Funda\FundaBundle\Entity\AgencyReportManager;

class ReportController extends Controller
{
    /**
     * Instance of FundaConnector
     * 
     * @var FundaConnector 
     */
    private $fundaConnector = null;
    
    /**
     * Instance of AgencyReportManager
     * 
     * @var AgencyReportManager 
     */
    private $agencyReportManager = null;
    
    /**
     * Lazy initialization of FundaConnector instance
     * 
     * @return FundaConnector
     */
    private function getFundaConnector() {
        if (!$this->fundaConnector) {
            $this->fundaConnector = new FundaConnector(
                $this->container->getParameter('funda_apikey'),
                $this->get('api_caller')
            );
        }
        
        return $this->fundaConnector;
    }
    
    /**
     * Lazy initialization of AgencyReportManager instance
     * 
     * @return AgencyReportManager
     */
    private function getAgencyReportManager() {
        if (!$this->agencyReportManager) {
            $this->agencyReportManager = new AgencyReportManager($this->getDoctrine()->getManager());;
        }
        
        return $this->agencyReportManager;
    }
    
    /**
     * Return configured report(s)
     * 
     * - pages - how much pages to get (each page contains 25 objects)
     * - name - report title
     * - params - search criteria
     * 
     * @param $reportId Id of report, NULL to return all reports
     * @return array
     */
    private function getReport($reportId = NULL) 
    {
        $reports = array(
            1 => array(
                'pages' => 10,
                'name' => 'Houses in Amsterdam (with garden)',
                'params' => array(
                    'type' => 'koop',
                    'zo' => '/amsterdam/tuin/',
                )    
            ),
            2 => array(
                'pages' => 10,
                'name' => 'Houses in Amsterdam (any)',
                'params' => array(
                    'type' => 'koop',
                    'zo' => '/amsterdam/',
                ) 
            ),
        );
        
        if (isset($reportId)) {
            return $reports[$reportId];
        }
        else {
            return $reports;
        }
    }
    
    /**
     * Load report data from Funda and save in DB
     * 
     * @param integer $reportId
     */
    private function loadReport($reportId) {
        $funda = $this->getFundaConnector();
        $reportManager = $this->getAgencyReportManager();
        
        $report = $this->getReport($reportId);
        
        if ($report) {
            // load raw report results from Funda
            $reportRawData = $funda->getAgenciesData($report['params'], $report['pages']);
            // save report results for further usage
            $reportManager->save($reportId, $reportRawData);
        }
        else {
            throw new Exception('Report [' . $reportId .'] not found', 404);
        }
    }
    
    /**
     * Display TOP10 results for all reports
     * 
     * @return Response
     */
    public function topAction()
    {
        $reportManager = $this->getAgencyReportManager();
        
        foreach ($this->getReport() as $reportId => $reportParams) {
            $topAgencies = $reportManager->getTop($reportId);
            
            // Load report results when it is empty
            // Can be triggered when application is running with empty cache
            if (!$topAgencies) {
                $this->loadReport($reportId);
                $topAgencies = $reportManager->getTop($reportId);
            }
            
            $reportResults[] = array(
                'name' => $reportParams['name'],
                'total' => $reportParams['pages'] * FundaConnector::OBJECTS_PER_PAGE,
                'top' => $topAgencies,
            );
        }
        
        return $this->render('EugefFundaBundle:Report:index.html.twig', array('reports' => $reportResults));
    }
    
    /**
     * Update all reports with a new data
     * 
     * @return Response
     */
    public function UpdateAction() {
        foreach ($this->getReport() as $reportId => $reportParams) {
            $this->loadReport($reportId);
        }
        
        return $this->redirect(
            $this->generateUrl('eugef_funda_report')
        );
    }
}
