<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\Roles;
use EmployeeBundle\Entity\Designation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use \DateTime;

class EmployeeController extends Controller
{
    /**
     * @Route("/auth/add-new-employee",name="addNewEmployee")
     */
    public function addNewEmployeeAction()
    {


      $user= $this->getUser();

              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_HR"){
              $allowedAccess = 'true';
              break;
            }
          }
          if($allowedAccess == 'true'){ 
            $designations = $this->getDoctrine()->getRepository(Designation::class)->findAll();
            $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();

            $em = $this->getDoctrine()->getManager();

                 $query = $em->createQuery("SELECT MAX(o.nID) as maxColumn FROM EmployeeBundle:Employee o ");
                  $results = $query->getResult();
                  foreach($results as $result){
                    $newEmpId =  ++$result['maxColumn'];
                    break;
                  }


                  
                    return $this->render('EmployeeBundle:Employee:add_new_employee.html.twig', array(
                        'designations'=>$designations,
                        'newEmpId'=>$newEmpId,
                        'roles'=>$roles,
                    ));
          }else{
             return $this->redirectToRoute('accessDenied',array(
            ));
          }
    


           
    }

    /**
     * @Route("/auth/new-employee-submit",name="submitNewEmployee",methods={"POST"})
     */
    public function submitNewEmployeeAction(Request $request ,UserPasswordEncoderInterface $encoder)
    {
            $user= $this->getUser();

              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_HR"){
              $allowedAccess = 'true';
              break;
            }
          }
          if($allowedAccess == 'true'){ 
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
                return $this->redirectToRoute('addNewEmployee',array(
            ));

          }else{
             return $this->redirectToRoute('accessDenied',array(
            ));
          }
           
      }

    /**
     * @Route("/auth/delete-employee",name="deleteEmployee")
     */
    public function deleteEmployeeAction()
    {
        return $this->render('EmployeeBundle:Employee:delete_employee.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/auth/update-employee",name="updateEmployee")
     */
    public function updateEmployeeAction()
    {
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        return $this->render('EmployeeBundle:Employee:update_employee.html.twig', array(
            'employees'=>$employees,
        ));
    }
     /**
     * @Route("/auth/update-employee/{empId}",name="updateEmployeeWithId")
     */
    public function updateEmployeeWithIdAction($empId)
    {
        $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $empId, )
              );
        $designations = $this->getDoctrine()->getRepository(Designation::class)->findAll();
        $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();


        return $this->render('EmployeeBundle:Employee:update_employee_single.html.twig', array(
            'employee'=>$employee,
            'designations'=>$designations,
                        'roles'=>$roles,
        ));
    }
      /**
     * @Route("/auth/updateEmployeeSubmit",name="updateEmployeeSubmit",methods={"POST"})
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
        return $this->redirectToRoute('updateEmployee',array(
            ));


       
    }

    

    /**
     * @Route("/auth/get-all-Employees",name="getAllEmployeeList")
     */
    public function getAllEmployeesAction()
    {
        return $this->render('EmployeeBundle:Employee:get_all_employees.html.twig', array(
            // ...
        ));
    }

}
