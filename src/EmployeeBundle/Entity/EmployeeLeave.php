<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EmployeeBundle\Entity\LeaveTypes;
/**
 * EmployeeLeave
 *
 * @ORM\Table(name="employee_leave")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\EmployeeLeaveRepository")
 */
class EmployeeLeave
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
     * @ORM\ManyToOne(targetEntity="LeaveTypes", inversedBy="employeeLeaves")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $leaveTypes;

      public function getleaveTypes()
    {
        return $this->leaveTypes;
    }

    public function setleaveTypes(LeaveTypes $leavetype)
    {
        $this->leaveTypes = $leavetype;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="numberOfDays", type="integer")
     */
    private $numberOfDays;

    /**
     * @var string
     *
     * @ORM\Column(name="leaveStatus", type="string", length=255)
     */
    private $leaveStatus;

    /** 
        @ORM\Column(name="fromDate",type="string", length=255)
     */
    private $fromDate;

      /** 
        @ORM\Column(name="toDate",type="string", length=255)
     */
    private $toDate;

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
     * Set fromDate
     *
     * @param string $fromDate
     *
     * @return EmployeeLeave
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

     /**
         * Set toDate
         *
         * @param string $toDate
         *
         * @return EmployeeLeave
         */
        public function setToDate($toDate)
        {
            $this->toDate = $toDate;

            return $this;
        }

         /**
     * Get toDate
     *
     * @return string
     */
    public function getToDate()
    {
        return $this->toDate;
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
     * Set employeeId
     *
     * @param string $employeeId
     *
     * @return EmployeeLeave
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
     * @return EmployeeLeave
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
     * Set numberOfDays
     *
     * @param integer $numberOfDays
     *
     * @return EmployeeLeave
     */
    public function setNumberOfDays($numberOfDays)
    {
        $this->numberOfDays = $numberOfDays;

        return $this;
    }

    /**
     * Get numberOfDays
     *
     * @return int
     */
    public function getNumberOfDays()
    {
        return $this->numberOfDays;
    }

    /**
     * Set leaveStatus
     *
     * @param string $leaveStatus
     *
     * @return EmployeeLeave
     */
    public function setLeaveStatus($leaveStatus)
    {
        $this->leaveStatus = $leaveStatus;

        return $this;
    }

    /**
     * Get leaveStatus
     *
     * @return string
     */
    public function getLeaveStatus()
    {
        return $this->leaveStatus;
    }
}

