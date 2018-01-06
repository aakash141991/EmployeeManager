<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\RequestTicket;
use EmployeeBundle\Entity\Department;
use Symfony\Component\HttpFoundation\Request;
use EmployeeBundle\Entity\Status;
use Symfony\Component\HttpFoundation\JsonResponse;
use EmployeeBundle\Entity\Employee;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use \DateTime;

class RequestController extends Controller
{
    /**
     * @Route("/auth/view-requests",name="viewRequests")
     */
    public function seeRequestsAction()
    {
    	 $user = $this->getUser();
        $requestTickets = $this->getDoctrine()->getRepository(RequestTicket::class)->findBy(
                array('employeeId' => $user->getNID(), )
              );
        return $this->render('EmployeeBundle:Request:see_requests.html.twig', array(
            'requestTickets'=>$requestTickets,
        ));
    }

     /**
     * @Route("/auth/raise-request",name="raiseRequests")
     */
    public function raiseRequestsAction(Request $request)
    {
    	$user = $this->getUser();
    	 $departments = $this->getDoctrine()->getRepository(Department::class)->findAll();
    	  $message=$request->query->get('message');
            if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
      
        return $this->render('EmployeeBundle:Request:raise_request.html.twig', array(
            'user'=>$user,
            'departments'=>$departments,
            'message'=>$message,
        	'errormessage'=>$errormessage,
        ));
    }
    /**
     * @Route("/auth/submit-request",name="requestTicketSubmit",methods={"POST"})
     */
    public function submitRequestsAction(Request $request)
    {	
    	try{


    	$user = $this->getUser();
    	$title= $request->request->get('title');
    	$description=$request->request->get('description');
    	$department_id=$request->request->get('department_id');

    	$requestTicket = new RequestTicket();
    	$requestTicket->setDescription($description);
    	$requestTicket->setTitle($title);
    	$requestTicket->setStatus('pending');
    	$requestTicket->setCreated(new DateTime());
    	$requestTicket->setDepartment($department_id);
    	$requestTicket->setEmployeeId($user->getNID());
    	if(($title == "")||($description=="")||($department_id == "")){
    			 $message="";
               $errorMessage= "Please fill all the fields before submitting";
    	}else{

        $department = $this->getDoctrine()->getRepository(Department::class)->find($department_id);
        $requestTicket->setAssignedTo( $department->getDepartmentHead());
        $depart_head = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
          array('nID'=>$department->getDepartmentHead()));
    	$en = $this->getDoctrine()->getManager();
                $en->persist($requestTicket);
                $en->flush();
                $message="Your Request ticket has been successfully submitted";
               $errorMessage= "";

               if(isset($depart_head )){
                /* Send Mail*/
              $subject="New Request Ticket created";
              $viewPath='EmployeeBundle:MailBody:request_success.html.twig';
              $fromEmail='admin@nettantra.net';
              $toEmail=$depart_head->getEmail();
              $name=$depart_head->getName();

                $email = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody(
                    $this->renderView(
                        $viewPath,
                        array('name' => $name,'title' => $title,)
                    ),'text/html');
                 $this->get('mailer')->send($email);
               }
                
    	}


        }catch (\Exception $e){
                
                $message="";
               $errorMessage= $e->getMessage();
               //$errorMessage= "something went wrong. Please fill the form properly";
              
               }
        return $this->redirectToRoute('raiseRequests',array(
        	'message'=>$message,
        	'errormessage'=>$errorMessage,
            ));
    }	

    /**
     * @Route("/auth/respond-request",name="respondRequest")
     */
    public function respondRequestsAction(Request $request)
    {
    	$user = $this->getUser();
    	$allStatus = $this->getDoctrine()->getRepository(Status::class)->findAll();
      	 $requestTickets = $this->getDoctrine()->getRepository(RequestTicket::class)->findBy(
                array('assignedTo' => $user->getNID(),
                'status'=>'pending', )
              );
        return $this->render('EmployeeBundle:Request:respond_request.html.twig', array(
        	'requestTickets'=>$requestTickets,
        	'allStatus'=>$allStatus,

        ));
    }

     /**
     * @Route("/auth/respondRequestSubmit/{requestId}",name="respondRequestSubmit")
     */
    public function respondRequestSubmitAction(Request $request,$requestId)
    {
    	$user = $this->getUser();
    	$ticket = $this->getDoctrine()->getRepository(RequestTicket::class)->find($requestId);
    $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy( array('nID' => $ticket->getEmployeeId(), ));
      $id =$ticket->getId();
      $assignedTo=$ticket->getAssignedTo();
      if($assignedTo == $user->getNID()){
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

      }else{
          return $this->redirectToRoute('accessDenied',array(
            ));

      }

    
    
    }

    	 /**
     * @Route("/auth/searchEmployeesList",name="searchEmployeesList")
     */
    public function getEmployeeSearchAction(Request $request)
    {
    	$search = $request->query->get('search_keyword');
    	//$results = $this->getDoctrine()->getRepository(Employee::class)->search($search);
      $nid = "";
      $name="";

        $em = $this->getDoctrine()->getManager();

         $query = $em->createQuery("SELECT o FROM EmployeeBundle:Employee o WHERE  o.name like :searchterm or o.nID like :searchterm")
      ->setParameter('searchterm', '%'.$search.'%');
      $results = $query->getResult();

			   foreach($results as $result){
			   	$name = $result->getName();
			   	$nid = $result->getNID();
          break;
			   }

    	return new JsonResponse(array(
          'name'=>$name,
          'nid'=>$nid,
                
          ));
   }
    
    	

    

    
    
    

}
