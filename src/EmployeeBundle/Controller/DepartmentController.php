<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Department;
use EmployeeBundle\Entity\Roles;
use EmployeeBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;

class DepartmentController extends Controller
{

	/**
     * @Route("/admin/update-department",name="updateDepartments")
     */
    public function updateDepartmentsAction(Request $request)
    {

        $departments = $this->getDoctrine()->getRepository(Department::class)->findAll();
         $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        return $this->render('EmployeeBundle:Admin:update_departments.html.twig', array(
            'departments'=>$departments,
            'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }

    /**
     * @Route("/admin/update-department/{depId}",name="updateDepartmentSingle")
     */
    public function updateDepartmentSingleAction(Request $request,$depId)
    {

        $department = $this->getDoctrine()->getRepository(Department::class)->find($depId);
         $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        return $this->render('EmployeeBundle:Admin:update_departments_single.html.twig', array(
            'department'=>$department,
            'allEmployees'=>$employees,
            'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }

    
        /**
     * @Route("/admin/update-department-submit",name="updateDepartmentSubmit")
     */
    public function updateDepartmentSubmitAction(Request $request)
    {
        try{

           $depId = $request->request->get('depId');
            $message="";
                  $errorMessage="";
           if(!isset($depId)){
                return $this->redirectToRoute('updateDepartments',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
           }else{
                 $departmentName = $request->request->get('departmentName');
                  $departmentHead= $request->request->get('departmentHead');
     
                  $department = $this->getDoctrine()->getRepository(Department::class)->find($depId);
                  $department->setDepartmentName($departmentName);
                  $Hod = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                          array('nID' => $departmentHead, )
                        );
                  if(isset($Hod )){
                     $department->setDepartmentHead($departmentHead);
                    
                     $en = $this->getDoctrine()->getManager();
                            $en->merge($department);
                            $en->flush();
                            $message="success";
                  }else{
                    $message="";
                         $errorMessage= "Please Provide correct Employee Id for Department Head";
                  }
       
           }
       
       
         

           }catch (\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }
               $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
         
        return $this->render('EmployeeBundle:Admin:update_departments_single.html.twig', array(
            'department'=>$department,
            'allEmployees'=>$employees,
            'message'=>$message,
                'errormessage'=>$errorMessage,
        ));
    }
    
    /**
     * @Route("/admin/add-new-department",name="addNewDepartment")
     */
    public function addNewDepartmentsAction(Request $request)
    {
        $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();
        return $this->render('EmployeeBundle:Admin:add_department.html.twig', array(
            'roles'=>$roles,
            'message'=>$message,
                'errormessage'=>$errormessage,
                'allEmployees'=>$employees,
        ));
    }
      /**
     * @Route("/admin/add-department-submit",name="addDepartmentSubmit")
     */
    public function addDepartmentSubmitAction(Request $request)
    {
        try{

        
        $departmentName = $request->request->get('departmentName');
        $departmentHead= $request->request->get('departmentHead');
        
       
        $message="";
        $errorMessage="";
        $department = new Department();
        $department->setDepartmentName($departmentName);
        $Hod = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $departmentHead, )
              );
        if(isset($Hod )){
           $department->setDepartmentHead($departmentHead);
          
           $en = $this->getDoctrine()->getManager();
                  $en->persist($department);
                  $en->flush();
                  $message="success";
        }else{
          $message="";
               $errorMessage= "Please Provide correct Employee Id for Department Head";
        }
       
       
         

           }catch (\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }

       return $this->redirectToRoute('addNewDepartment',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }

    /**
     * @Route("/admin/delete-department/{depId}")
     */
    public function deleteDepartmentAction($depId)
    {
        $department = $this->getDoctrine()->getRepository(Department::class)->find($depId);
        $en = $this->getDoctrine()->getManager();
                  $en->remove($department);
                  $en->flush();
                  $message="success";
                  $errorMessage = "" ;
        return $this->redirectToRoute('updateDepartments',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }
    

}
