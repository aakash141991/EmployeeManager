<?php

namespace EmployeeBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="EmployeeBundle\Repository\EmployeeRepository")
 */
class Employee implements UserInterface
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=100, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="NID", type="string", length=255, unique=true)
     */
    private $nID;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=500, nullable=true)
     */
    private $address;

        /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

        /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, unique=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="roles",type="json_array")
     */
    private $roles = [];

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfBirth", type="datetime", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=100, nullable=true)
     */
    private $gender;

     /**
     * @var string
     *
     * @ORM\Column(name="managerNid", type="string", length=255)
     */
    private $managerNid;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=255,nullable=true)
     */
    private $designation;

     /**
     * @var string
     *
     * @ORM\Column(name="panNumber", type="string", length=100,nullable=true)
     */
    private $panNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="employeeStatus", type="string", length=100,nullable=true)
     */
    private $employeeStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfJoining", type="datetime", nullable=true)
     */
    private $dateOfJoining;

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
     * @return Employee
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

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Employee
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

     /**
     * Set username
     *
     * @param string $username
     *
     * @return Employee
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Employee
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

         /**
     * Set password
     *
     * @param string $password
     *
     * @return Employee
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set nID
     *
     * @param string $nID
     *
     * @return Employee
     */
    public function setNID($nID)
    {
        $this->nID = $nID;

        return $this;
    }

    /**
     * Get nID
     *
     * @return string
     */
    public function getNID()
    {
        return $this->nID;
    }
     /**
     * Set managerNid
     *
     * @param string $managerNid
     *
     * @return Employee
     */
    public function setManagerNid($managerNid)
    {
        $this->managerNid = $managerNid;

        return $this;
    }

    /**
     * Get managerNid
     *
     * @return string
     */
    public function getManagerNid()
    {
        return $this->managerNid;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Employee
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

        /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return Employee
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

      /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Employee
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * get roles
     */
    public function getRoles()
    {
       $roles = $this->roles;
        if (!in_array('ROLE_EMPLOYEE', $roles)) {
            $roles[] = 'ROLE_EMPLOYEE';
        }
        return $roles;

    }

     /**
     * Set roles
     */
     public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

      public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }
     public function eraseCredentials()
    {
        return null;
    }

          /**
     * Set panNumber
     *
     * @param string $panNumber
     *
     * @return Employee
     */
    public function setPanNumber($panNumber)
    {
        $this->panNumber = $panNumber;

        return $this;
    }

    /**
     * Get panNumber
     *
     * @return string
     */
    public function getPanNumber()
    {
        return $this->panNumber;
    }
          /**
     * Set designation
     *
     * @param string $designation
     *
     * @return Employee
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }
    

              /**
     * Set employeeStatus
     *
     * @param string $employeeStatus
     *
     * @return Employee
     */
    public function setEmployeeStatus($employeeStatus)
    {
        $this->employeeStatus = $employeeStatus;

        return $this;
    }

    /**
     * Get employeeStatus
     *
     * @return string
     */
    public function getEmployeeStatus()
    {
        return $this->employeeStatus;
    }
            /**
     * Set dateOfJoining
     *
     * @param \DateTime $dateOfJoining
     *
     * @return Employee
     */
    public function setDateOfJoining($dateOfJoining)
    {
        $this->dateOfJoining = $dateOfJoining;

        return $this;
    }

    /**
     * Get dateOfJoining
     *
     * @return \DateTime
     */
    public function getDateOfJoining()
    {
        return $this->dateOfJoining;
    }
    
}

