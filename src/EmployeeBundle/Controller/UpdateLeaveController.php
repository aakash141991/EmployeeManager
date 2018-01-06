<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\LeaveTypes;
use EmployeeBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;

class UpdateLeaveController extends Controller
{

	    /**
     * @Route("/admin/update-leaves",name="updateLeaveType")
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
     * @Route("/admin/update-leaves/{leaveTypeId}",name="updateLeaveTypeSingle")
     */
    public function updateLeaveTypeSingleAction($leaveTypeId,Request $request)
    {
         $leaveType = $this->getDoctrine()->getRepository(LeaveTypes::class)->find($leaveTypeId);
         $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        return $this->render('EmployeeBundle:LeaveType:update_leave_single.html.twig', array(
            'leaveType'=>$leaveType,  
            'message'=>$message,
                'errormessage'=>$errormessage,
            ));
    }

      /**
     * @Route("/admin/update-leave-submit",name="updateLeaveSubmit")
     */
    public function updateLeaveSubmitAction(Request $request)
    {

        try{
            $errorMessage="";
            $message="";
      $leaveTypeId=$request->request->get('leaveTypeId');;
      
       if(!isset($leaveTypeId)){
       		 return $this->redirectToRoute('updateLeaveType',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
       }else{
       		 $leaveType = $this->getDoctrine()->getRepository(LeaveTypes::class)->find($leaveTypeId);
		       	$leaveType->setTypeName($request->request->get('leaveName'));
		       $leaveType->setDaysAlloted($request->request->get('daysAlloted'));
		       $leaveType->setdocumentsRequired($request->request->get('document'));
		       $en = $this->getDoctrine()->getManager();
		                $en->merge( $leaveType);
		                $en->flush();
		       $message="Leave Type Updated Successfully";
       }
       
       }catch(\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }
       return $this->render('EmployeeBundle:LeaveType:update_leave_single.html.twig', array(
            'leaveType'=>$leaveType,  
            'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }
   


}
