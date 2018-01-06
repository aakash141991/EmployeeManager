<?php

namespace EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attendance
 *
 * @ORM\Table(name="attendance")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\AttendanceRepository")
 */
class Attendance
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
     * @var int
     *
     * @ORM\Column(name="month", type="integer")
     */
    private $month;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;


    /**
     * @var int
     *
     * @ORM\Column(name="absentDays", type="integer")
     */
    private $absentDays;

    /**
     * @var int
     *
     * @ORM\Column(name="totalDays", type="integer")
     */
    private $totalDays;
    /**
     * @var int
     *
     * @ORM\Column(name="presentDays", type="integer")
     */
    private $presentDays;

     /**
     * @var float
     *
     * @ORM\Column(name="salaryDeducted", type="float",nullable=true)
     */
    private $salaryDeducted;



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
     * Set employeeId
     *
     * @param string $employeeId
     *
     * @return Attendance
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;

        return $this;
    }

     /**
     * Set salaryDeducted
     *
     * @param float $salaryDeducted
     *
     * @return Attendance
     */
    public function setSalaryDeducted($salaryDeducted)
    {
        $this->salaryDeducted = $salaryDeducted;

        return $this;
    }

    /**
     * Get salaryDeducted
     *
     * @return float
     */
    public function getSalaryDeducted()
    {
        return $this->salaryDeducted;
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
     * Set month
     *
     * @param integer $month
     *
     * @return Attendance
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Attendance
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
     * Set absentDays
     *
     * @param integer $absentDays
     *
     * @return Attendance
     */
    public function setAbsentDays($absentDays)
    {
        $this->absentDays = $absentDays;

        return $this;
    }

    /**
     * Get absentDays
     *
     * @return int
     */
    public function getAbsentDays()
    {
        return $this->absentDays;
    }
    
    /**
     * Set totalDays
     *
     * @param integer $totalDays
     *
     * @return Attendance
     */
    public function setTotalDays($totalDays)
    {
        $this->totalDays = $totalDays;

        return $this;
    }

    /**
     * Get totalDays
     *
     * @return int
     */
    public function getTotalDays()
    {
        return $this->totalDays;
    }


    /**
     * Set presentDays
     *
     * @param integer $presentDays
     *
     * @return Attendance
     */
    public function setPresentDays($presentDays)
    {
        $this->presentDays = $presentDays;

        return $this;
    }

    /**
     * Get presentDays
     *
     * @return int
     */
    public function getPresentDays()
    {
        return $this->presentDays;
    }
}

