<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaxSlab
 *
 * @ORM\Table(name="tax_slab")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\TaxSlabRepository")
 */
class TaxSlab
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
     * @var float
     *
     * @ORM\Column(name="incomeFrom", type="float")
     */
    private $incomeFrom;

    /**
     * @var float
     *
     * @ORM\Column(name="incomeUpto", type="float")
     */
    private $incomeUpto;

    /**
     * @var float
     *
     * @ORM\Column(name="taxRate", type="float")
     */
    private $taxRate;
    /**
     * @var float
     *
     * @ORM\Column(name="cess", type="float")
     */
    private $cess;




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
     * Set incomeFrom
     *
     * @param float $incomeFrom
     *
     * @return TaxSlab
     */
    public function setIncomeFrom($incomeFrom)
    {
        $this->incomeFrom = $incomeFrom;

        return $this;
    }

    /**
     * Get incomeFrom
     *
     * @return float
     */
    public function getIncomeFrom()
    {
        return $this->incomeFrom;
    }

    /**
     * Set incomeUpto
     *
     * @param float $incomeUpto
     *
     * @return TaxSlab
     */
    public function setIncomeUpto($incomeUpto)
    {
        $this->incomeUpto = $incomeUpto;

        return $this;
    }

    /**
     * Get incomeUpto
     *
     * @return float
     */
    public function getIncomeUpto()
    {
        return $this->incomeUpto;
    }

    /**
     * Set taxRate
     *
     * @param float $taxRate
     *
     * @return TaxSlab
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Get taxRate
     *
     * @return float
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }

     /**
     * Set cess
     *
     * @param float $cess
     *
     * @return TaxSlab
     */
    public function setCess($cess)
    {
        $this->cess = $cess;

        return $this;
    }

    /**
     * Get cess
     *
     * @return float
     */
    public function getCess()
    {
        return $this->cess;
    }



    
}

