<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Department
 *
 * @ORM\Table(name="department")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\DepartmentRepository")
 */
class Department
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
     * @ORM\Column(name="departmentName", type="string", length=255)
     */
    private $departmentName;

    /**
     * @var string
     *
     * @ORM\Column(name="departmentRole", type="string", length=255)
     */
    private $departmentRole;

    /**
     * @var string
     *
     * @ORM\Column(name="departmentHead", type="string", length=255)
     */
    private $departmentHead;


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
     * Set departmentName
     *
     * @param string $departmentName
     *
     * @return Department
     */
    public function setDepartmentName($departmentName)
    {
        $this->departmentName = $departmentName;

        return $this;
    }

    /**
     * Get departmentName
     *
     * @return string
     */
    public function getDepartmentName()
    {
        return $this->departmentName;
    }

    /**
     * Set departmentRole
     *
     * @param string $departmentRole
     *
     * @return Department
     */
    public function setDepartmentRole($departmentRole)
    {
        $this->departmentRole = $departmentRole;

        return $this;
    }

    /**
     * Get departmentRole
     *
     * @return string
     */
    public function getDepartmentRole()
    {
        return $this->departmentRole;
    }

        /**
     * Set departmentHead
     *
     * @param string $departmentHead
     *
     * @return Department
     */
    public function setDepartmentHead($departmentHead)
    {
        $this->departmentHead = $departmentHead;

        return $this;
    }

    /**
     * Get departmentHead
     *
     * @return string
     */
    public function getDepartmentHead()
    {
        return $this->departmentHead;
    }
}

