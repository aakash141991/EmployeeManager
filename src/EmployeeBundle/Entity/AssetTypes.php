<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * AssetTypes
 *
 * @ORM\Table(name="asset_types")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\AssetTypesRepository")
 */
class AssetTypes
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
     * @ORM\Column(name="typeName", type="string", length=255)
     */
    private $typeName;

    /**
     * @var float
     *
     * @ORM\Column(name="approxValue", type="float", nullable=true)
     */
    private $approxValue;

        /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeAsset", mappedBy="assetType")
     */
    private $employeeAssets;

      public function __construct()
    {
        $this->employeeAssets = new ArrayCollection();
    }
    /**
     * @return Collection|EmployeeAsset[]
     */
    public function getEmployeeAssets()
    {
        return $this->employeeAssets;
    }

    /**
     * @return Collection|EmployeeLeave[]
     */
    public function getEmployeeLeaves()
    {
        return $this->employeeLeaves;
    }


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
     * Set typeName
     *
     * @param string $typeName
     *
     * @return AssetTypes
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;

        return $this;
    }

    /**
     * Get typeName
     *
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * Set approxValue
     *
     * @param float $approxValue
     *
     * @return AssetTypes
     */
    public function setApproxValue($approxValue)
    {
        $this->approxValue = $approxValue;

        return $this;
    }

    /**
     * Get approxValue
     *
     * @return float
     */
    public function getApproxValue()
    {
        return $this->approxValue;
    }

        /**
     * Set description
     *
     * @param string $description
     *
     * @return AssetTypes
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

