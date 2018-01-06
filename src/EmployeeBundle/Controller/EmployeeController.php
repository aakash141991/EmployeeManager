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
    public function addNewEmployeeAction(Request $request)
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
          $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
          if($allowedAccess == 'true'){ 
            $allEmployees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
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
                        'allEmployees'=>$allEmployees,
                        'message'=>$message,
                'errormessage'=>$errormessage,
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


          $valid= true;
          $message="";
          $errorMessage="";
          $managerNid = $request->request->get('managerNid');
          $empId = $request->request->get('employeeId');
          $name=$request->request->get('name');
          $email= $request->request->get('email');
          $phone= $request->request->get('phone');
          $panNo=$request->request->get('panNumber');
          $desgId = $request->request->get('designation');
          $gender = $request->request->get('gender');
          $doj = new DateTime($request->request->get('doj'));
          $dob = new DateTime($request->request->get('dob'));
          $currentDate = new DateTime();
          $rolesAll= array();
              $rolesArray = $request->request->get('role');

             /* validation starts*/
          if(isset($managerNid )&& isset($empId )&&isset($name)&&isset($email)&&isset($phone)&&isset($panNo)&&isset($desgId)&&isset($doj)&&isset($dob) && isset($gender)&&isset($rolesArray)){

             $manager =  $this->getDoctrine()->getRepository(Employee::class)->findOneBy(array('nID' => $managerNid ) );
             $designation = $this->getDoctrine()->getRepository(Designation::class)->find($desgId );
             $availableEmp =  $this->getDoctrine()->getRepository(Employee::class)->findOneBy(array('nID' => $empId) );

                if(!isset( $manager )){
                   $valid= false;
                   $errorMessage ="Please recheck Manager Id Provided";
                 }elseif(!isset($designation)){
                  $valid= false;
                    $errorMessage ="Please recheck Designation Provided";
                 }elseif(isset($availableEmp)){
                  $valid= false;
                    $errorMessage ="Employee Id provided already exists ";
                 }

                 $empAge =($currentDate->diff($dob )->days)/365;
                 if($empAge < 18){
                   $valid= false;
                    $errorMessage ="Employee must be atleast 18 years Old ";
                 }elseif($doj < $dob){
                   $valid= false;
                    $errorMessage ="Date Of joining is not correct";
                 }






          }else{
            $errorMessage ="Please fill all fields";
             $valid= false;
          }
          /* validation ends*/

          if($allowedAccess == 'true'){ 


             if($valid){
                  $employee= new Employee();
              $employee->setNID( $empId);
               $employee->setName($name);
               $employee->setEmail($email);
              $employee->setUsername($email);
              $employee->setPhone($phone);
              
               $employee->setManagerNid($managerNid);
               $employee->setAddress($request->request->get('address'));     
               $employee->setPanNumber($panNo);
               $employee->setDesignation($designation);
               $employee->setEmployeeStatus('active');
              $employee->setDateOfJoining($doj );
              $employee->setGender($gender);
              $employee->setDateOfBirth($dob);

              $encoded = $encoder->encodePassword($employee, 'Welcome');
              $employee->setPassword($encoded);

              
              foreach ($rolesArray as $role) {
               array_push($rolesAll,$role);
              }

           
             $employee-> setRoles($rolesAll);
            

              $en = $this->getDoctrine()->getManager();
                $en->persist($employee);
                $en->flush();
                $message="Employee Added Successfully";
             }
            
                return $this->redirectToRoute('addNewEmployee',array(
                  'message'=>$message,
                'errormessage'=>$errorMessage,
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

        $allEmployees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        return $this->render('EmployeeBundle:Employee:update_employee_single.html.twig', array(
            'employee'=>$employee,
            'designations'=>$designations,
                        'roles'=>$roles,
                        'allEmployees'=>$allEmployees,
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
               
               $desgId = $request->request->get('designation');
               $designation = $this->getDoctrine()->getRepository(Designation::class)->find($desgId );
               $employee->setDesignation($designation);

               $employee->setEmployeeStatus($request->request->get('active'));
              $employee->setDateOfJoining(new DateTime($request->request->get('doj')));
              $employee->setGender($request->request->get('gender'));
              $employee->setDateOfBirth(new DateTime($request->request->get('dob')));

              //$encoded = $encoder->encodePassword($employee, 'Welcome');
              //$employee->setPassword($encoded);

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
