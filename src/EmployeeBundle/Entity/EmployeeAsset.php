<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployeeAsset
 *
 * @ORM\Table(name="employee_asset")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\EmployeeAssetRepository")
 */
class EmployeeAsset
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
     * @ORM\Column(name="employeeId", type="string", length=255)
     */
    private $employeeId;

      /**
     * @var string
     *
     * @ORM\Column(name="employeeName", type="string", length=255)
     */
    private $employeeName;

    /**
     * @var string
     *
     * @ORM\Column(name="assetType", type="string", length=255)
     */
    private $assetType;

    /**
     * @var bool
     *
     * @ORM\Column(name="isAssigned", type="boolean")
     */
    private $isAssigned;

    /**
     * @var bool
     *
     * @ORM\Column(name="isRequested", type="boolean")
     */
    private $isRequested;

     /**
     * @var bool
     *
     * @ORM\Column(name="isRejected", type="boolean")
     */
    private $isRejected;

    /**
     * @var string
     *
     * @ORM\Column(name="assetId", type="string", length=255, nullable=true)
     */
    private $assetId;

     /**
     * @var int
     *
     * @ORM\Column(name="assetTypeId", type="integer")
     */
    private $assetTypeId;

     /** 
        @ORM\Column(name="fromDate",type="string", length=255)
     */
    private $fromDate;



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
     * Get assetTypeId
     *
     * @return int
     */
    public function getAssetTypeId()
    {
        return $this->assetTypeId;
    }

    /**
     * Set assetTypeId
     *
     * @param int $assetTypeId
     *
     * @return EmployeeAsset
     */
    public function setAssetTypeId($assetTypeId)
    {
        $this->assetTypeId = $assetTypeId;

        return $this;
    }

    /**
     * Set employeeId
     *
     * @param string $employeeId
     *
     * @return EmployeeAsset
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
     * Set employeeName
     *
     * @param string $employeeName
     *
     * @return EmployeeAsset
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
     * Set assetType
     *
     * @param string $assetType
     *
     * @return EmployeeAsset
     */
    public function setAssetType($assetType)
    {
        $this->assetType = $assetType;

        return $this;
    }

    /**
     * Get assetType
     *
     * @return string
     */
    public function getAssetType()
    {
        return $this->assetType;
    }

    /**
     * Set isAssigned
     *
     * @param boolean $isAssigned
     *
     * @return EmployeeAsset
     */
    public function setIsAssigned($isAssigned)
    {
        $this->isAssigned = $isAssigned;

        return $this;
    }

    /**
     * Get isAssigned
     *
     * @return bool
     */
    public function getIsAssigned()
    {
        return $this->isAssigned;
    }

    /**
     * Set isRequested
     *
     * @param boolean $isRequested
     *
     * @return EmployeeAsset
     */
    public function setIsRequested($isRequested)
    {
        $this->isRequested = $isRequested;

        return $this;
    }

    /**
     * Get isRequested
     *
     * @return bool
     */
    public function getIsRequested()
    {
        return $this->isRequested;
    }

    /**
     * Set assetId
     *
     * @param string $assetId
     *
     * @return EmployeeAsset
     */
    public function setAssetId($assetId)
    {
        $this->assetId = $assetId;

        return $this;
    }

    /**
     * Get assetId
     *
     * @return string
     */
    public function getAssetId()
    {
        return $this->assetId;
    }

    /**
     * Set fromDate
     *
     * @param string $fromDate
     *
     * @return EmployeeAsset
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

     /**
     * Get fromDate
     *
     * @return string
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

     /**
     * Set isRejected
     *
     * @param boolean $isRejected
     *
     * @return EmployeeAsset
     */
    public function setIsRejected($isRejected)
    {
        $this->isRejected = $isRejected;

        return $this;
    }

    /**
     * Get isRejected
     *
     * @return bool
     */
    public function getIsRejected()
    {
        return $this->isRejected;
    }
}

