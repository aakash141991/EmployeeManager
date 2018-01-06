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
use \DateTime;
use \DatePeriod;
use \DateInterval;
class LeaveController extends Controller
{
    /**
     * @Route("/auth/leave-history",name="leavehistory")
     */
    public function leaveHistoryAction()
    {

      $user = $this->getUser();
      $empId= $user->getNID();
    	$leaves = $this->getDoctrine()->getRepository(EmployeeLeave::class)->findBy(
        array('employeeId' => $empId,
          ));
    	
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

               $takenLeaveType =  $leave->getleaveTypes();
               if(('approved'==$leave->getLeaveStatus())||('pending'==$leave->getLeaveStatus())){
                $takenDays= $leave->getNumberOfDays();
              }else{
                $takenDays= 0;
              }
               
               foreach ($leaveRecord as $key => $value) {
                 if($key == $takenLeaveType->getTypeName() ){
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
            $days_remaining = 0;
            $weekends=0;
             $isvalid = true;
              $message="";
              $errorMessage= "";
            $leaveTypeId=$request->request->get('leave_type');
            $fromDate=$request->request->get('from_date');
            $toDate=$request->request->get('to_date');


            if(isset( $leaveTypeId) && isset($fromDate) && isset($toDate)){
              $leaveType= $this->getDoctrine()->getRepository(LeaveTypes::class)->find($leaveTypeId);
            $leave->setleaveTypes($leaveType);
            $leave->setLeaveStatus('pending');
            
            $start = strtotime($fromDate);
            $end = strtotime($toDate);
            $days_taken = ceil(($end - $start) / 86400);

            $leave->setFromDate($fromDate);
            $leave->setToDate($toDate);
            
            $days_taken =$days_taken +1;
            $leaveFromDate =new DateTime($fromDate);
             $leaveTillDate =new DateTime($toDate);
             $formatted_leave_from =  date_format($leaveFromDate,"Y/m/d ");
            $formatted_leave_till = date_format($leaveTillDate,"Y/m/d");
        

          $interval =  DateInterval::createFromDateString('1 day');
          $period = new DatePeriod($leaveFromDate, $interval, $leaveTillDate);

          foreach ( $period as $dt ){
            $dtString =   $dt->format( "Y-m-d" );
            $weekDay = date('N', strtotime($dtString));
             
               if( $weekDay == 6 || $weekDay == 7){
                $weekends ++  ;
               }
          }
      
          $days_taken =$days_taken - $weekends;
          $leave->setNumberOfDays($days_taken);
                      

               $leaves = $this->getDoctrine()->getRepository(EmployeeLeave::class)->findBy(
                array('employeeId' => $user->getNID(), )
              );
               $leaveTypes = $this->getDoctrine()->getRepository(LeaveTypes::class)->findAll();
             $leaveRecord = array("key" => "value");
              foreach ($leaveTypes as  $leavetype) {
                     $leaveRecord[$leavetype->getTypeName()] = $leavetype->getDaysAlloted();
                  }
                  $leaveTotal=$leaveRecord ;
              foreach ($leaves as $leaveSingle) {

                     $takenLeaveType =  $leaveSingle->getleaveTypes();
                     if(('approved'==$leaveSingle->getLeaveStatus())||('pending'==$leaveSingle->getLeaveStatus())){

                        $leaveSingleFrom = new DateTime($leaveSingle->getFromDate());
                        $leaveSingleTill = new DateTime($leaveSingle->getToDate());
                        $dateAppliedFrom =date_format($leaveSingleFrom, 'Y-m-d');
                        $dateAppliedTill=date_format( $leaveSingleTill, 'Y-m-d');

                      if((strtotime($formatted_leave_from) < strtotime($dateAppliedFrom)) && (strtotime($formatted_leave_till) < strtotime($dateAppliedFrom ))){

                        $takenDays= $leaveSingle->getNumberOfDays();

                      }elseif((strtotime($formatted_leave_from) > strtotime($dateAppliedTill)) ){
                          $takenDays= $leaveSingle->getNumberOfDays();
                      }else{
                        $isvalid = false;
                          $errorMessage= "Leave has already been applied for selected days, please see History";
                            break;
                      }
                    

                      
                    }else{
                      $takenDays= 0;
                    }
                     
                     foreach ($leaveRecord as $key => $value) {
                       if($key == $takenLeaveType->getTypeName() ){
                         $value = $value - $takenDays;
                         $leaveRecord[$key]=$value;
                         break;
                       }
                    }
                  }
                foreach ($leaveRecord as $key => $value) {
                  if($key == $leaveType->getTypeName() ){
                     $days_remaining = $value;
                      break;
                     }
                    
                  } 
            }else{
                $isvalid = false;
                $errorMessage= "Please Fill the form correctly";
            }

     

              if($days_taken > $days_remaining  ){
                  $isvalid = false;
                  $errorMessage= "You have only " . $days_remaining . " days of " . $leaveType->getTypeName() . " left";
              }
               if($days_taken <=0){
                
                 $isvalid = false;
               $errorMessage= "Please select proper dates and apply again";
              }

                if( $isvalid){
                 
                    $en = $this->getDoctrine()->getManager();
                    $en->persist($leave);
                    $en->flush();
                     $message="Leave Request has been sent for approval";
                    $errorMessage="";

                    try{
                      $manager = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $user->getManagerNid(), )
              );
                      if(isset($manager)){
                        $name = $manager->getName();
                        $subject="Respond to Leave request";
                      $viewPath='EmployeeBundle:MailBody:leave_request.html.twig';
                      $requester = $user->getName();
                      //$toEmail =$manager->getEmail();
                      $toEmail="aakash.kumar@nettantra.net";
                      $fromEmail="admin@nettantra.net";
                        
                      $email = \Swift_Message::newInstance()
                      ->setSubject($subject)
                      ->setFrom($fromEmail)
                      ->setTo($toEmail)
                      ->setBody(
                          $this->renderView(
                              $viewPath,
                              array('name' => $name,'requester'=> $requester,)
                          ),'text/html');
                       $this->get('mailer')->send($email);
                      }
                      
                      
                     

                    }catch (\Exception $e){
                      $message="Leave Request has been sent for approval, Mail sending Failed";
                    
               }
                   
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
