<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Designation;
use EmployeeBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;

class DesignationController extends Controller
{

	
    /**
     * @Route("/admin/update-designation",name="updateDesignation")
     */
    public function updateDesignationAction(Request $request)
    {

      $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        $designations = $this->getDoctrine()->getRepository(Designation::class)->findAll();
        return $this->render('EmployeeBundle:Admin:update_designation.html.twig', array(
            'designations'=>$designations,
            'message'=>$message,
                'errormessage'=>$errormessage,
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
     * @Route("/admin/update-designation/{desgId}",name="updateSingleDesignation")
     */
    public function addDesignationSingleAction(Request $request,$desgId)
    {
        $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }

            $designation = $this->getDoctrine()->getRepository(Designation::class)->find($desgId);
        return $this->render('EmployeeBundle:Designation:update_designation_single.html.twig', array(
        	'designation'=>$designation,
            'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }

    /**
     * @Route("/admin/update-designation-submit",name="updateDesignationSubmit")
     */
    public function updateDesignationSubmitAction(Request $request)
    {
        try{

        
        $designationName = $request->request->get('designationName');
       
        $desId = $request->request->get('desgId');
       
        $message="";
        $errorMessage="";
        $designation = $this->getDoctrine()->getRepository(Designation::class)->find($desId);
        $designation->setName($designationName);
      
           $en = $this->getDoctrine()->getManager();
                  $en->merge($designation);
                  $en->flush();
                  $message="Updated successfully";
       
       
         

           }catch (\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }

         return $this->render('EmployeeBundle:Designation:update_designation_single.html.twig', array(
        	'designation'=>$designation,
            'message'=>$message,
                'errormessage'=>$errorMessage,
        ));
    }
    /**
     * @Route("/admin/delete-designation/{desgId}")
     */
    public function deleteDepartmentAction($desgId)
    {
        $designation = $this->getDoctrine()->getRepository(Designation::class)->find($desgId);
        $en = $this->getDoctrine()->getManager();
                  $en->remove($designation);
                  $en->flush();
                  $message=" Removed successfully";
                  $errorMessage = "" ;
        return $this->redirectToRoute('updateDesignation',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }
   
    
    
}
