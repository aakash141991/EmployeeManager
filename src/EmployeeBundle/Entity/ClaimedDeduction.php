<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClaimedDeduction
 *
 * @ORM\Table(name="claimed_deduction")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\ClaimedDeductionRepository")
 */
class ClaimedDeduction
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
     * @ORM\Column(name="deduction", type="float")
     */
    private $deduction;

    /**
     * @var string
     *
     * @ORM\Column(name="employeeId", type="string", length=255)
     */
    private $employeeId;

    /**
     * @var string
     *
     * @ORM\Column(name="document", type="string", length=255, nullable=true)
     */
    private $document;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isApproved", type="boolean")
     */
    private $isApproved;

     /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

     /**
     * @var string
     *
     * @ORM\Column(name="assignedTo", type="string", length=255,nullable=true)
     */
    private $assignedTo;

     /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255,nullable=true)
     */
    private $description;

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
     * Set isApproved
     *
     * @param boolean $isApproved
     *
     * @return ClaimedDeduction
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * Get isApproved
     *
     * @return boolean
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }
    /**
     * Set deduction
     *
     * @param float $deduction
     *
     * @return ClaimedDeduction
     */
    public function setDeduction($deduction)
    {
        $this->deduction = $deduction;

        return $this;
    }

    /**
     * Get deduction
     *
     * @return float
     */
    public function getDeduction()
    {
        return $this->deduction;
    }

    /**
     * Set employeeId
     *
     * @param string $employeeId
     *
     * @return ClaimedDeduction
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
     * Set document
     *
     * @param string $document
     *
     * @return ClaimedDeduction
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

        /**
     * Set year
     *
     * @param integer $year
     *
     * @return ClaimedDeduction
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }
    
         /**
     * Set assignedTo
     *
     * @param string $assignedTo
     *
     * @return ClaimedDeduction
     */
    public function setAssignedTo($assignedTo)
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * Get assignedTo
     *
     * @return string
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

        /**
     * Set description
     *
     * @param string $description
     *
     * @return ClaimedDeduction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

}

