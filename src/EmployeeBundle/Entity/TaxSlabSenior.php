<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaxSlabSenior
 *
 * @ORM\Table(name="tax_slab_senior")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\TaxSlabSeniorRepository")
 */
class TaxSlabSenior
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
     * @return TaxSlabSenior
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
     * @return TaxSlabSenior
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
     * @return TaxSlabSenior
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
}

