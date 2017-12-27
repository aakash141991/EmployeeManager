<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\EmployeeLeave;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\LeaveTypes;
use EmployeeBundle\Entity\LeaveFaq;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\JsonResponse;
class LeaveController extends Controller
{
    /**
     * @Route("/auth/leave-history",name="leavehistory")
     */
    public function leaveHistoryAction()
    {
    	$leaves = $this->getDoctrine()->getRepository(EmployeeLeave::class)->findAll();
    	
        return $this->render('EmployeeBundle:Leave:leave_history.html.twig', array(
            'leaves'=> $leaves,
        ));
    }
      /**
     * @Route("/auth/apply-leave",name="applyleave")
     */
    public function applyLeaveAction(Request $request)
    {
          $user = $this->getUser();
        $leaves = $this->getDoctrine()->getRepository(EmployeeLeave::class)->findBy(
                array('employeeId' => $user->getNID(), )
              );
        $faqs =  $this->getDoctrine()->getRepository(LeaveFaq::class)->findAll();
        $leaveTypes = $this->getDoctrine()->getRepository(LeaveTypes::class)->findAll();
       $leaveRecord = array("key" => "value");
        foreach ($leaveTypes as  $leavetype) {
               $leaveRecord[$leavetype->getTypeName()] = $leavetype->getDaysAlloted();
            }
            $leaveTotal=$leaveRecord ;
        foreach ($leaves as $leave) {

               $takenLeaveType =  $leave->getLeaveType();
               $takenDays= $leave->getNumberOfDays();
               foreach ($leaveRecord as $key => $value) {
                 if($key == $takenLeaveType ){
                   $value = $value - $takenDays;
                   $leaveRecord[$key]=$value;
                   break;
                 }
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
      
        return $this->render('EmployeeBundle:Leave:apply_leave.html.twig', array(
            'leaves'=> $leaves,
            'user'=>$user,
            'leaveTypes'=>$leaveTypes,
            'leaveRecord'=>$leaveRecord,
            'leaveTotal'=>$leaveTotal,
            'faqs'=>$faqs,
            'message'=>$message,
            'errormessage'=>$errormessage,
        ));
    }
    /**
     * @Route("/auth/apply-leave-submit",name="applyLeaveSubmit",methods={"POST"})
     */
        public function applyLeaveSubmit(Request $request)
        {
          try{
            $user = $this->getUser();
            $leave = new EmployeeLeave();
            $employeeId = $user->getNID();
            $employeeName = $user->getName();
            $leave->setEmployeeId($employeeId);
            $leave->setEmployeeName($employeeName);

            $leaveType=$request->request->get('leave_type');
            $leave->setLeaveType($leaveType);
            $leave->setLeaveStatus('pending');

            $fromDate=$request->request->get('from_date');
            $toDate=$request->request->get('to_date');
            
            $start = strtotime($fromDate);
            $end = strtotime($toDate);
            $days_taken = ceil(($end - $start) / 86400);

            $leave->setFromDate($fromDate);
            $leave->setToDate($toDate);
            $leave->setNumberOfDays($days_taken);

             
              if($days_taken <=0){
                 $message="";
               $errorMessage= "Please select proper dates and apply again";
              }else{
                $en = $this->getDoctrine()->getManager();
                $en->persist($leave);
                $en->flush();
                $message="Leave Request has been sent for approval";
                $errorMessage="";
              }

              

             }catch (\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
              
               }

            
             return $this->redirectToRoute('applyleave',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
        
      }

    /**
     * @Route("/auth/respondLeave",name="respondLeave",methods={"GET"})
     */
        public function respondLeaveAction(Request $request)
        {
          $user= $this->getUser();

              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_MANAGER"){
              $allowedAccess = 'true';
              break;
            }
          }

      if($allowedAccess == 'true'){ 
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findBy(
                array('managerNid' => $user->getNID(), )
              );
          $leaveRequests = array();
          foreach($employees as $employee){
            $employeeId = $employee->getNID();

            $leaves = $this->getDoctrine()->getRepository(EmployeeLeave::class)->findBy(
                array(
                  'employeeId' => $employee->getNID(),
                  'leaveStatus'=>'pending',
                 ));
            foreach ($leaves as $leave ) {
               array_push($leaveRequests, $leave);
            
            }
           
          }
          return $this->render('EmployeeBundle:Leave:respond_leave.html.twig', array(
                  'leaveRequests' => $leaveRequests,
              ));
      }else{
          return $this->redirectToRoute('accessDenied',array(
            ));
      }


          

             
          }


    /**
     * @Route("/auth/respondLeaveSubmit/{leaveId}",name="respondLeaveSubmit",methods={"GET"})
     */
        public function respondLeaveSubmitAction(Request $request,$leaveId)
        {
          $resp = $request->query->get('resp');
          $leave = $this->getDoctrine()->getRepository(EmployeeLeave::class)->find($leaveId);
          $employeeId = $leave->getEmployeeId();
           $employees=$this->getDoctrine()->getRepository(Employee::class)->findBy(
                array(
                  'nID' => $employeeId,
                 ));
           foreach($employees as $employee){
                        $toEmail=$employee->getEmail();
                        break;
                      }
            $name=$leave->getEmployeeName();
            $fromEmail='admin@nettantra.net';

          if($resp == 'yes'){
            $leave->setLeaveStatus('approved');

            $subject="Leave Request accepted";
                      $viewPath='EmployeeBundle:MailBody:leave_accepted.html.twig';

          }elseif( $resp =='no'){
            $leave->setLeaveStatus('rejected');

            $subject="Leave Request Rejected";
              $viewPath='EmployeeBundle:MailBody:leave_rejected.html.twig';
          }
          $en = $this->getDoctrine()->getManager();
                $en->merge($leave);
                $en->flush();

                 /* Send Mail*/
                $email = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody(
                    $this->renderView(
                        $viewPath,
                        array('name' => $name,'leaveId' => $leaveId,)
                    ),'text/html');
                 $this->get('mailer')->send($email);

          return new JsonResponse(array(
          'data'=>'true',
                
          ));
          
          }
         
        
      


}
