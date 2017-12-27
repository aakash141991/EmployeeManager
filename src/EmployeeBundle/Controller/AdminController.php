<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Department;
use EmployeeBundle\Entity\Roles;
use EmployeeBundle\Entity\LeaveTypes;
use EmployeeBundle\Entity\Designation;
use Symfony\Component\HttpFoundation\Request;
class AdminController extends Controller
{
    /**
     * @Route("/admin/update-department")
     */
    public function updateDepartmentsAction()
    {

        $departments = $this->getDoctrine()->getRepository(Department::class)->findAll();
        return $this->render('EmployeeBundle:Admin:update_departments.html.twig', array(
            'departments'=>$departments,
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
        $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();
        return $this->render('EmployeeBundle:Admin:add_department.html.twig', array(
            'roles'=>$roles,
            'message'=>$message,
                'errormessage'=>$errormessage,
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
        $role= $request->request->get('role');
       
        $message="";
        $errorMessage="";
        $department = new Department();
        $department->setDepartmentName($departmentName);
        $department->setDepartmentHead($departmentHead);
         $department->setDepartmentRole($role);
       
         $en = $this->getDoctrine()->getManager();
                $en->persist($department);
                $en->flush();
                $message="success";

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
     * @Route("/admin/update-roles")
     */
    public function updateRolesAction()
    {
        $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();
        return $this->render('EmployeeBundle:Admin:update_roles.html.twig', array(
            'roles'=>$roles,
        ));
    }

    /**
     * @Route("/admin/update-designation")
     */
    public function updateDesignationAction()
    {
        $designations = $this->getDoctrine()->getRepository(Designation::class)->findAll();
        return $this->render('EmployeeBundle:Admin:update_designation.html.twig', array(
            'designations'=>$designations,
        ));
    }
     /**
     * @Route("/admin/add-new-designation",name="addDesignation")
     */
    public function addDesignationAction(Request $request)
    {
        $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }

        return $this->render('EmployeeBundle:Admin:add_designation.html.twig', array(
            'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }
     /**
     * @Route("/admin/add-designation-submit",name="addDesignationSubmit")
     */
    public function addDesignationSubmitAction(Request $request)
    {
        try{
            $errorMessage="";
      
       $designation= new Designation();
       $designation->setName($request->request->get('name'));
       $en = $this->getDoctrine()->getManager();
                $en->persist( $designation);
                $en->flush();
       $message="Designation Added Successfully";
       }catch(\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }
        return $this->redirectToRoute('addDesignation',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }
    

    /**
     * @Route("/admin/update-leaves")
     */
    public function updateLeavesAction()
    {
         $leaveTypes = $this->getDoctrine()->getRepository(LeaveTypes::class)->findAll();
        return $this->render('EmployeeBundle:Admin:update_leaves.html.twig', array(
            'leaveTypes'=>$leaveTypes,  
            ));
    }
    /**
     * @Route("/admin/add-new-leave",name="addNewLeave")
     */
    public function addLeaveAction(Request $request)
    {
        $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }

         
        return $this->render('EmployeeBundle:Admin:add_leave.html.twig', array(
              'message'=>$message,
                'errormessage'=>$errormessage,
            ));
    }
    /**
     * @Route("/admin/add-leave-submit",name="addLeaveSubmit")
     */
    public function addLeaveSubmitAction(Request $request)
    {

        try{
            $errorMessage="";
      
       $leaveType= new LeaveTypes();
       $leaveType->setTypeName($request->request->get('leaveName'));
       $leaveType->setDaysAlloted($request->request->get('daysAlloted'));
       $leaveType->setdocumentsRequired($request->request->get('document'));
       $en = $this->getDoctrine()->getManager();
                $en->persist( $leaveType);
                $en->flush();
       $message="Leave Type Added Successfully";
       }catch(\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }
        return $this->redirectToRoute('addNewLeave',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }

      /**
     * @Route("/admin/add-new-role",name="addNewRole")
     */
    public function addRoleAction(Request $request)
    {
        $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }

         
        return $this->render('EmployeeBundle:Admin:add_role.html.twig', array(
              'message'=>$message,
                'errormessage'=>$errormessage,
            ));
    }

    /**
     * @Route("/admin/add-role-submit",name="addRoleSubmit")
     */
    public function addRoleSubmitAction(Request $request)
    {

        try{
            $errorMessage="";
      
       $role= new Roles();
       $role->setRoleName($request->request->get('roleName'));
       $role->setRoleActionName($request->request->get('roleAction'));
       
       $en = $this->getDoctrine()->getManager();
                $en->persist( $role);
                $en->flush();
       $message="Leave Type Added Successfully";
       }catch(\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }
        return $this->redirectToRoute('addNewRole',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }

   
    

}
