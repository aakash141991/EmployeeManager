<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LeaveTypes
 *
 * @ORM\Table(name="leave_types")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\LeaveTypesRepository")
 */
class LeaveTypes
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
     * @var int
     *
     * @ORM\Column(name="daysAlloted", type="integer")
     */
    private $daysAlloted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="documentsRequired", type="boolean")
     */
    private $documentsRequired;

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
     * @return LeaveTypes
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
     * Set daysAlloted
     *
     * @param integer $daysAlloted
     *
     * @return LeaveTypes
     */
    public function setDaysAlloted($daysAlloted)
    {
        $this->daysAlloted = $daysAlloted;

        return $this;
    }

    /**
     * Get daysAlloted
     *
     * @return int
     */
    public function getDaysAlloted()
    {
        return $this->daysAlloted;
    }

    /**
     * Set documentsRequired
     *
     * @param boolean $documentsRequired
     *
     * @return LeaveTypes
     */
    public function setDocumentsRequired($documentsRequired)
    {
        $this->documentsRequired = $documentsRequired;

        return $this;
    }

    /**
     * Get documentsRequired
     *
     * @return boolean
     */
    public function getDocumentsRequired()
    {
        return $this->documentsRequired;
    }
}

