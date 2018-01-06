<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;
use EmployeeBundle\Entity\Roles;

class RoleController extends Controller
{

	/**
     * @Route("/admin/update-roles", name="updateRoles")
     */
    public function updateRolesAction(Request $request)
    {
        $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();
        $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        return $this->render('EmployeeBundle:Admin:update_roles.html.twig', array(
            'roles'=>$roles,
            'message'=>$message,
                'errormessage'=>$errormessage,
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
       $message="Role Added Successfully";
       }catch(\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }
        return $this->redirectToRoute('addNewRole',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }

    /**
     * @Route("/admin/update-roles/{roleId}",name="updateRoleSingle")
     */
    public function updateRoleSingleAction($roleId,Request $request)
    {
        $role = $this->getDoctrine()->getRepository(Roles::class)->find($roleId);
         $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        return $this->render('EmployeeBundle:Role:update_role_single.html.twig', array(
            'role'=>$role,
            'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }

      /**
     * @Route("/admin/update-role-submit",name="updateRoleSubmit")
     */
    public function updateRoleSubmitAction(Request $request)
    {

    	 $roleId = $request->request->get('roleId');
        try{
            $errorMessage="";
            $message="";
      if(!isset($roleId)){
                return $this->redirectToRoute('updateRoles',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
           }else{
           		$role = $this->getDoctrine()->getRepository(Roles::class)->find($roleId);
		       $role->setRoleName($request->request->get('roleName'));
		       $role->setRoleActionName($request->request->get('roleAction'));
		       
		       $en = $this->getDoctrine()->getManager();
		                $en->merge( $role);
		                $en->flush();
		       $message="Role Updated Successfully";
		           }
      
       }catch(\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }
       return $this->render('EmployeeBundle:Role:update_role_single.html.twig', array(
            'role'=>$role,
            'message'=>$message,
                'errormessage'=>$errorMessage,
        ));
    }

       /**
     * @Route("/admin/delete-role/{roleId}")
     */
    public function deleteDepartmentAction($roleId)
    {
        $role = $this->getDoctrine()->getRepository(Roles::class)->find($roleId);
        $en = $this->getDoctrine()->getManager();
                  $en->remove($role);
                  $en->flush();
                  $message="Role removed Successfully";
                  $errorMessage = "" ;
        return $this->redirectToRoute('updateRoles',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }
    

}
