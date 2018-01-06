<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Designation
 *
 * @ORM\Table(name="designation")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\DesignationRepository")
 */
class Designation
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

     /**
     * @ORM\OneToMany(targetEntity="Employee", mappedBy="designation")
     */
    private $employee;

      public function __construct()
    {
        $this->employee = new ArrayCollection();
    }

    /**
     * @return Collection|Employee[]
     */
    public function getEmployee()
    {
        return $this->employee;
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
     * Set name
     *
     * @param string $name
     *
     * @return Designation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

