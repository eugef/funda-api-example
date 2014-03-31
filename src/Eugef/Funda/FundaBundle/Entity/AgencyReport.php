<?php

namespace Eugef\Funda\FundaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AgencyReport
 */
class AgencyReport
{
    /**
     * @var integer
     */
    private $report;

    /**
     * @var integer
     */
    private $agencyId;

    /**
     * @var string
     */
    private $agencyName;

    /**
     * @var integer
     */
    private $amount;


    /**
     * Set report
     *
     * @param integer $report
     * @return AgencyReport
     */
    public function setReport($report)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report
     *
     * @return integer 
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Set agencyId
     *
     * @param integer $agencyId
     * @return AgencyReport
     */
    public function setAgencyId($agencyId)
    {
        $this->agencyId = $agencyId;

        return $this;
    }

    /**
     * Get agencyId
     *
     * @return integer 
     */
    public function getAgencyId()
    {
        return $this->agencyId;
    }

    /**
     * Set agencyName
     *
     * @param string $agencyName
     * @return AgencyReport
     */
    public function setAgencyName($agencyName)
    {
        $this->agencyName = $agencyName;

        return $this;
    }

    /**
     * Get agencyName
     *
     * @return string 
     */
    public function getAgencyName()
    {
        return $this->agencyName;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return AgencyReport
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
