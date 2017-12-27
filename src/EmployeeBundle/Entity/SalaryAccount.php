<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalaryAccount
 *
 * @ORM\Table(name="salary_account")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\SalaryAccountRepository")
 */
class SalaryAccount
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
     * @ORM\Column(name="bankName", type="string", length=255)
     */
    private $bankName;

    /**
     * @var string
     *
     * @ORM\Column(name="ifscCode", type="string", length=100)
     */
    private $ifscCode;

    /**
     * @var string
     *
     * @ORM\Column(name="accountNumber", type="string", length=255)
     */
    private $accountNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="employeeName", type="string", length=255)
     */
    private $employeeName;

     /**
     * @var string
     *
     * @ORM\Column(name="employeeId", type="string", length=255,unique=true)
     */
    private $employeeId;

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
     * Set bankName
     *
     * @param string $bankName
     *
     * @return SalaryAccount
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set ifscCode
     *
     * @param string $ifscCode
     *
     * @return SalaryAccount
     */
    public function setIfscCode($ifscCode)
    {
        $this->ifscCode = $ifscCode;

        return $this;
    }

    /**
     * Get ifscCode
     *
     * @return string
     */
    public function getIfscCode()
    {
        return $this->ifscCode;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     *
     * @return SalaryAccount
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set employeeName
     *
     * @param string $employeeName
     *
     * @return SalaryAccount
     */
    public function setEmployeeName($employeeName)
    {
        $this->employeeName = $employeeName;

        return $this;
    }

    /**
     * Get employeeName
     *
     * @return string
     */
    public function getEmployeeName()
    {
        return $this->employeeName;
    }

     /**
     * Set employeeId
     *
     * @param string $employeeId
     *
     * @return SalaryAccount
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


    
}

