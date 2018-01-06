<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="employeeId", type="string", length=255, unique=true)
     */
    private $employeeId;

    /**
     * @var float
     *
     * @ORM\Column(name="basicSalary", type="float")
     */
    private $basicSalary;

    /**
     * @var float
     *
     * @ORM\Column(name="hra", type="float",nullable=true)
     */
    private $hra;

    /**
     * @var float
     *
     * @ORM\Column(name="specialAllowance", type="float",nullable=true)
     */
    private $specialAllowance;

    /**
     * @var float
     *
     * @ORM\Column(name="conveyanceAllowance", type="float",nullable=true)
     */
    private $conveyanceAllowance;

    /**
     * @var float
     *
     * @ORM\Column(name="pfContribution", type="float",nullable=true)
     */
    private $pfContribution;

    /**
     * @var float
     *
     * @ORM\Column(name="incomeTax", type="float",nullable=true)
     */
    private $incomeTax;

     /**
     * @var float
     *
     * @ORM\Column(name="totalEarning", type="float",nullable=true)
     */
    private $totalEarning;
      /**
     * @var float
     *
     * @ORM\Column(name="totalDeduction", type="float",nullable=true)
     */
    private $totalDeduction;


       /**
     * @var float
     *
     * @ORM\Column(name="netSalary", type="float")
     */
    private $netSalary;

     /**
     * @var string
     *
     * @ORM\Column(name="updatedBy", type="string", length=255,nullable=true)
     */
    private $updatedBy;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set employeeId
     *
     * @param string $employeeId
     *
     * @return Payment
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    /**
     * Get employeeId
     *
     * @return string
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * Set basicSalary
     *
     * @param float $basicSalary
     *
     * @return Payment
     */
    public function setBasicSalary($basicSalary)
    {
        $this->basicSalary = $basicSalary;

        return $this;
    }

    /**
     * Get basicSalary
     *
     * @return float
     */
    public function getBasicSalary()
    {
        return $this->basicSalary;
    }

    /**
     * Set hra
     *
     * @param float $hra
     *
     * @return Payment
     */
    public function setHra($hra)
    {
        $this->hra = $hra;

        return $this;
    }

    /**
     * Get hra
     *
     * @return float
     */
    public function getHra()
    {
        return $this->hra;
    }

    /**
     * Set specialAllowance
     *
     * @param float $specialAllowance
     *
     * @return Payment
     */
    public function setSpecialAllowance($specialAllowance)
    {
        $this->specialAllowance = $specialAllowance;

        return $this;
    }

    /**
     * Get specialAllowance
     *
     * @return float
     */
    public function getSpecialAllowance()
    {
        return $this->specialAllowance;
    }

    /**
     * Set conveyanceAllowance
     *
     * @param float $conveyanceAllowance
     *
     * @return Payment
     */
    public function setConveyanceAllowance($conveyanceAllowance)
    {
        $this->conveyanceAllowance = $conveyanceAllowance;

        return $this;
    }

    /**
     * Get conveyanceAllowance
     *
     * @return float
     */
    public function getConveyanceAllowance()
    {
        return $this->conveyanceAllowance;
    }

    /**
     * Set pfContribution
     *
     * @param float $pfContribution
     *
     * @return Payment
     */
    public function setPfContribution($pfContribution)
    {
        $this->pfContribution = $pfContribution;

        return $this;
    }

    /**
     * Get pfContribution
     *
     * @return float
     */
    public function getPfContribution()
    {
        return $this->pfContribution;
    }

    /**
     * Set incomeTax
     *
     * @param float $incomeTax
     *
     * @return Payment
     */
    public function setIncomeTax($incomeTax)
    {
        $this->incomeTax = $incomeTax;

        return $this;
    }

    /**
     * Get incomeTax
     *
     * @return float
     */
    public function getIncomeTax()
    {
        return $this->incomeTax;
    }


    
     /**
     * Set updatedBy
     *
     * @param string $updatedBy
     *
     * @return Payment
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
    


    /**
     * Set totalEarning
     *
     * @param float $totalEarning
     *
     * @return Payment
     */
    public function setTotalEarning($totalEarning)
    {
        $this->totalEarning = $totalEarning;

        return $this;
    }

    /**
     * Get totalEarning
     *
     * @return float
     */
    public function getTotalEarning()
    {
        return $this->totalEarning;
    }



    /**
     * Set totalDeduction
     *
     * @param float $totalDeduction
     *
     * @return Payment
     */
    public function setTotalDeduction($totalDeduction)
    {
        $this->totalDeduction = $totalDeduction;

        return $this;
    }

    /**
     * Get totalDeduction
     *
     * @return float
     */
    public function getTotalDeduction()
    {
        return $this->totalDeduction;
    }

 
    /**
     * Set netSalary
     *
     * @param float $netSalary
     *
     * @return Payment
     */
    public function setNetSalary($netSalary)
    {
        $this->netSalary = $netSalary;

        return $this;
    }

    /**
     * Get netSalary
     *
     * @return float
     */
    public function getNetSalary()
    {
        return $this->netSalary;
    }


}

