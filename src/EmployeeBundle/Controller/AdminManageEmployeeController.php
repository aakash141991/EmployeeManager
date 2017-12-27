<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\Attendance;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;
use EmployeeBundle\Entity\Roles;
use EmployeeBundle\Entity\Designation;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AdminManageEmployeeController extends Controller
{


	/**
     * @Route("/admin/manage-attendance",name="adminManageAttendance")
     */
    public function manageAttendanceAction()
    {

        return $this->render('EmployeeBundle:AdminEmployeeManage:manage_attendance.html.twig', array(
             'attendance'=>'',
            'employee'=>'',
            'showPrevious'=>'false',
        ));
    }

    /**
     * @Route("/admin/manage-attendance/{empId}",name="adminGetEmployeeAttendance")
     */
    public function getEmployeeDetailAttendance(Request $request,$empId)
    {

    	 $attendance="";
    	 $employee ="";
    	 $currentDate = new DateTime();
		 $currentMonth = date_format($currentDate, 'm');
		 $currentYear = date_format($currentDate, 'Y');
		 $showPrevious="false";

         $showPrevious = $request->query->get('showprevious');
          $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
		                array('nID' => $empId, )
		              );
         if(isset($showPrevious ) && $showPrevious == 'true'){

         	 $attendance = $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                array('employeeId' => $empId,
                'month'=>$currentMonth - 1 ,
                'year' => $currentYear, )
              );
         }else{
         	$showPrevious ="false";
    	   $attendance = $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                array('employeeId' => $empId,
                'month'=>$currentMonth ,
                'year' => $currentYear, )
              );
         }

    	
        return $this->render('EmployeeBundle:AdminEmployeeManage:manage_attendance.html.twig', array(
            'attendance'=>$attendance,
            'employee'=>$employee,
            'showPrevious'=>$showPrevious,
        ));
    }


     /**
     * @Route("/admin/add-new-employee",name="AdminAddNewEmployee")
     */
    public function addNewEmployeeAction()
    {

         
            $designations = $this->getDoctrine()->getRepository(Designation::class)->findAll();
            $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();

            $em = $this->getDoctrine()->getManager();

                 $query = $em->createQuery("SELECT MAX(o.nID) as maxColumn FROM EmployeeBundle:Employee o ");
                  $results = $query->getResult();
                  foreach($results as $result){
                    $newEmpId =  ++$result['maxColumn'];
                    break;
                  }


                  
                    return $this->render('EmployeeBundle:AdminEmployeeManage:add_new_employee.html.twig', array(
                        'designations'=>$designations,
                        'newEmpId'=>$newEmpId,
                        'roles'=>$roles,
                    ));
          
    


           
    }

    /**
     * @Route("/admin/new-employee-submit",name="AdminSubmitNewEmployee",methods={"POST"})
     */
    public function submitNewEmployeeAction(Request $request ,UserPasswordEncoderInterface $encoder)
    {
            $user= $this->getUser();
          
     
     
            $employee= new Employee();
              $employee->setNID( $request->request->get('employeeId'));
               $employee->setName($request->request->get('name'));
               $employee->setEmail($request->request->get('email'));
              $employee->setUsername($request->request->get('email'));
              $employee->setPhone($request->request->get('phone'));
              
               $employee->setManagerNid($request->request->get('managerNid'));
               $employee->setAddress($request->request->get('address'));     
               $employee->setPanNumber($request->request->get('panNumber'));
               $employee->setDesignation($request->request->get('designation'));
               $employee->setEmployeeStatus($request->request->get('active'));
              $employee->setDateOfJoining(new DateTime($request->request->get('doj')));
              $employee->setGender($request->request->get('gender'));
              $employee->setDateOfBirth(new DateTime($request->request->get('dob')));

              $encoded = $encoder->encodePassword($employee, 'Welcome');
              $employee->setPassword($encoded);

              $rolesAll= array();
              $rolesArray = $request->request->get('role');
              foreach ($rolesArray as $role) {
               array_push($rolesAll,$role);
              }

           
             $employee-> setRoles($rolesAll);
            

              $en = $this->getDoctrine()->getManager();
                $en->persist($employee);
                $en->flush();
                return $this->redirectToRoute('AdminAddNewEmployee',array(
            ));
           
      }

       /**
     * @Route("/admin/update-employee",name="AdminUpdateEmployee")
     */
    public function updateEmployeeAction()
    {
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        return $this->render('EmployeeBundle:AdminEmployeeManage:update_employee.html.twig', array(
            'employees'=>$employees,
        ));
    }
     /**
     * @Route("/admin/update-employee/{empId}",name="adminUpdateEmployeeWithId")
     */
    public function updateEmployeeWithIdAction($empId)
    {
        $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $empId, )
              );
        $designations = $this->getDoctrine()->getRepository(Designation::class)->findAll();
        $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();


        return $this->render('EmployeeBundle:AdminEmployeeManage:update_employee_single.html.twig', array(
            'employee'=>$employee,
            'designations'=>$designations,
                        'roles'=>$roles,
        ));
    }
      /**
     * @Route("/admin/updateEmployeeSubmit",name="adminUpdateEmployeeSubmit",methods={"POST"})
     */
    public function updateEmployeeSubmitAction(Request $request,UserPasswordEncoderInterface $encoder)
    {
      $empId=  $request->request->get('employeeId');
        $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $empId, )
              );
         $employee->setNID( $request->request->get('employeeId'));
               $employee->setName($request->request->get('name'));
               $employee->setEmail($request->request->get('email'));
              $employee->setUsername($request->request->get('email'));
              $employee->setPhone($request->request->get('phone'));
              
               $employee->setManagerNid($request->request->get('managerNid'));
               $employee->setAddress($request->request->get('address'));     
               $employee->setPanNumber($request->request->get('panNumber'));
               $employee->setDesignation($request->request->get('designation'));
               $employee->setEmployeeStatus($request->request->get('active'));
              $employee->setDateOfJoining(new DateTime($request->request->get('doj')));
              $employee->setGender($request->request->get('gender'));
              $employee->setDateOfBirth(new DateTime($request->request->get('dob')));

              $encoded = $encoder->encodePassword($employee, 'Welcome');
              $employee->setPassword($encoded);

              $rolesAll= array();
              $rolesArray = $request->request->get('role');
              foreach ($rolesArray as $role) {
               array_push($rolesAll,$role);
              }

             $employee-> setRoles($rolesAll);
            

              $en = $this->getDoctrine()->getManager();
                $en->merge($employee);
                $en->flush();
        return $this->redirectToRoute('AdminUpdateEmployee',array(
            ));


       
    }

}
