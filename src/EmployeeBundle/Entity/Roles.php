<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Roles
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\RolesRepository")
 */
class Roles
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
     * @ORM\Column(name="roleName", type="string", length=255)
     */
    private $roleName;

    /**
     * @var string
     *
     * @ORM\Column(name="roleActionName", type="string", length=255)
     */
    private $roleActionName;


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
     * Set roleName
     *
     * @param string $roleName
     *
     * @return Roles
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * Get roleName
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

      /**
     * Set roleActionName
     *
     * @param string $roleActionName
     *
     * @return Roles
     */
    public function setRoleActionName($roleActionName)
    {
        $this->roleActionName = $roleActionName;

        return $this;
    }

    /**
     * Get roleActionName
     *
     * @return string
     */
    public function getRoleActionName()
    {
        return $this->roleActionName;
    }
}

