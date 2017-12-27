<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RequestTicket
 *
 * @ORM\Table(name="request_ticket")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\RequestTicketRepository")
 */
class RequestTicket
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
     * @ORM\Column(name="description", type="string", length=500)
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="resolvedDate", type="datetime", nullable=true)
     */
    private $resolvedDate;

    /**
     * @var int
     *
     * @ORM\Column(name="department", type="integer", length=255)
     */
    private $department;

    /**
     * @var string
     *
     * @ORM\Column(name="employeeId", type="string", length=255)
     */
    private $employeeId;

    /**
     * @var string
     *
     * @ORM\Column(name="assignedTo", type="string", length=255,nullable=true)
     */
    private $assignedTo;

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
     * Set description
     *
     * @param string $description
     *
     * @return RequestTicket
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
    

       /**
     * Set title
     *
     * @param string $title
     *
     * @return RequestTicket
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return RequestTicket
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return RequestTicket
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set resolvedDate
     *
     * @param \DateTime $resolvedDate
     *
     * @return RequestTicket
     */
    public function setResolvedDate($resolvedDate)
    {
        $this->resolvedDate = $resolvedDate;

        return $this;
    }

    /**
     * Get resolvedDate
     *
     * @return \DateTime
     */
    public function getResolvedDate()
    {
        return $this->resolvedDate;
    }

    /**
     * Set department
     *
     * @param int $department
     *
     * @return RequestTicket
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return int
     */
    public function getDepartment()
    {
        return $this->department;
    }

     /**
     * Set employeeId
     *
     * @param string $employeeId
     *
     * @return RequestTicket
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
     * Set assignedTo
     *
     * @param string $assignedTo
     *
     * @return RequestTicket
     */
    public function setAssignedTo($assignedTo)
    {
        $this->setAssignedTo = $assignedTo;

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
    
}

