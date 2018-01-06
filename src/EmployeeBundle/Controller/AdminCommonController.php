<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\EmployeeLeave;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\LeaveTypes;
use EmployeeBundle\Entity\EmployeeAsset;
use EmployeeBundle\Entity\AssetTypes;
use EmployeeBundle\Entity\Payment;
use EmployeeBundle\Entity\Attendance;
use EmployeeBundle\Entity\Status;
use EmployeeBundle\Entity\RequestTicket;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use \DateTime;

class AdminCommonController extends Controller
{



    
    /**
     * @Route("/admin/respond-leave",name="adminRespondLeave")
     */
    public function respondLeaveAction()
    {
        $user= $this->getUser();

              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ADMIN"){
              $allowedAccess = 'true';
              break;
            }
          }

      if($allowedAccess == 'true'){ 
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
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
          return $this->render('EmployeeBundle:AdminCommon:respond_leave.html.twig', array(
                  'leaveRequests' => $leaveRequests,
              ));
      }else{
          return $this->redirectToRoute('accessDeniedAdmin',array(
            ));
      }


          

    }
    /**
     * @Route("/admin/respondLeaveSubmit/{leaveId}",name="respondLeaveAdminSubmit",methods={"GET"})
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
         

    /**
     * @Route("/admin/respond-asset-request",name="adminRespondAsset")
     */
    public function approveAssetAction()
    {


        $allowedAccess = 'false';
          $user = $this->getUser();
          $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ADMIN"){
              $allowedAccess = 'true';
              break;
            }
          }
          if($allowedAccess == 'true'){
                  $assetRequests = $this->getDoctrine()->getRepository(EmployeeAsset::class)->findBy(
                array(
                  'isRequested' => 1,
                 ));

                  return $this->render('EmployeeBundle:AdminCommon:respond_asset.html.twig', array(
                 'assetRequests'=>$assetRequests,
              ));
          }else{
              return $this->redirectToRoute('accessDenied',array(
            ));
          }
        
    }
    /**
     * @Route("/admin/respond-asset-submit/{assetId}",name="respondAssetAdmin",methods={"GET"})
     */
        public function respondAssetAdminSubmitAction(Request $request,$assetId)
        {
          $resp = $request->query->get('resp');
          $asset = $this->getDoctrine()->getRepository(EmployeeAsset::class)->find($assetId);
          $employeeId = $asset->getEmployeeId();
          
          $employees=$this->getDoctrine()->getRepository(Employee::class)->findBy(
                array(
                  'nID' => $employeeId,
                 ));
          foreach($employees as $employee){
                        $toEmail=$employee->getEmail();
                        break;
                      }
            $name=$asset->getEmployeeName();
            $fromEmail='admin@nettantra.net';

          if($resp == 'yes'){
                    $asset->setIsAssigned(1);
                    $asset->setIsRequested(0);
                    $date = new DateTime();
                    $dt = $date->format('d/m/Y');
                    $asset->setFromDate($dt);

                      $subject="Asset Request accepted";
                      $viewPath='EmployeeBundle:MailBody:asset_accepted.html.twig';

          }elseif( $resp =='no'){
              $asset->setIsRejected(1);
              $asset->setIsRequested(0);
              $subject="Asset Request Rejected";
              $viewPath='EmployeeBundle:MailBody:asset_rejected.html.twig';
          }
          $en = $this->getDoctrine()->getManager();
                $en->merge($asset);
                $en->flush();

                 /* Send Mail*/
                $email = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody(
                    $this->renderView(
                        $viewPath,
                        array('name' => $name,'assetId'=>$assetId)
                    ),'text/html');
                 $this->get('mailer')->send($email);

          return new JsonResponse(array(
          'data'=>'true',
                
          ));
          
          }


    /**
     * @Route("/admin/serve-request")
     */
    public function serveRequestAction()
    {
        return $this->render('EmployeeBundle:AdminCommon:serve_request.html.twig', array(
            // ...
        ));
    }

        /**
     * @Route("/admin/respond-request",name="adminRespondRequest")
     */
    public function respondRequestsAction(Request $request)
    {
      $user = $this->getUser();
      $allStatus = $this->getDoctrine()->getRepository(Status::class)->findAll();
         $requestTickets = $this->getDoctrine()->getRepository(RequestTicket::class)->findBy(
                array('status'=>'pending', )
              );
        return $this->render('EmployeeBundle:AdminCommon:respond_request.html.twig', array(
          'requestTickets'=>$requestTickets,
          'allStatus'=>$allStatus,

        ));
    }

     /**
     * @Route("/admin/respondRequestSubmit/{requestId}",name="adminRespondRequestSubmit")
     */
    public function respondRequestSubmitAction(Request $request,$requestId)
    {

      $user = $this->getUser();
      $ticket = $this->getDoctrine()->getRepository(RequestTicket::class)->find($requestId);
       $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy( array('nID' => $ticket->getEmployeeId(), ));
      $assignedTo=$ticket->getAssignedTo();
     
          $status = $request->query->get('status');
            if(isset($status)){
               
               $ticket->setStatus($status);
               $ticket->setResolvedDate(new DateTime());
                $en = $this->getDoctrine()->getManager();
                      $en->merge($ticket);
                      $en->flush();
              $subject="Request Ticket resolved";
              $viewPath='EmployeeBundle:MailBody:request_resolved.html.twig';
              $fromEmail='admin@nettantra.net';
              $toEmail=$employee->getEmail();
              $name=$employee->getName();

                $email = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody(
                    $this->renderView(
                        $viewPath,
                        array('name' => $name,'id' => $id,)
                    ),'text/html');
                 $this->get('mailer')->send($email);
            }else{
              $assigned = $request->query->get('assigned');
              if(isset($assigned )){
                $ticket->setAssignedTo($assigned);
                $en = $this->getDoctrine()->getManager();
                      $en->merge($ticket);
                      $en->flush();
                 }

             }

      return new JsonResponse(array(
          'data'=>'true',
                
          ));

      

    
    
    }

}
