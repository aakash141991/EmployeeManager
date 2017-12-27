<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\SalaryAccount;
use EmployeeBundle\Entity\PaySlip;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\Attendance;
use EmployeeBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;

class PaymentController extends Controller
{
    /**
     * @Route("/auth/accountDetails")
     */
    public function accountDetailsAction()
    {
    		$user = $this->getUser();
    		$employeeId = $user->getNID();
    	  $accountDetails = $this->getDoctrine()->getRepository(SalaryAccount::class)->findBy(
                array('employeeId' => $employeeId, )
              );
        return $this->render('EmployeeBundle:Payment:account_details.html.twig', array(
            'salaryAccounts'=>$accountDetails
        ));
    }

    /**
     * @Route("/auth/paySlips",name="viewPaySlips")
     */
    public function viewPaySlipsAction()
    {
        $user = $this->getUser();
        $employeeId=$user->getNID();
        $currentDate = new DateTime();
        $currentMonth = date_format($currentDate, 'm');
        $currentYear = date_format($currentDate, 'Y');

        $paySlipCurrent = $this->getDoctrine()->getRepository(PaySlip::class)->findBy(
                array('employeeId' => $employeeId,
                'month'=>$currentMonth ,
                'year' => $currentYear,
                )
              );
        $paySlipPrev = $this->getDoctrine()->getRepository(PaySlip::class)->findBy(
                array('employeeId' => $employeeId,
                'month'=>$currentMonth - 1 ,
                'year' => $currentYear,
                )
              );
        $paySlipAll = $this->getDoctrine()->getRepository(PaySlip::class)->findBy(
                array('employeeId' => $employeeId,
                'year' => $currentYear,
                )
              );
        $result = array_merge( $paySlipCurrent, $paySlipPrev );
        return $this->render('EmployeeBundle:Payment:view_PaySlips.html.twig', array(
            'paySlips'=> $result,
            'paySlipAll'=> $paySlipAll,
        ));
    }

    /**
     * @Route("/auth/Generate-Pay-Slips",name="generatePaySlip")
     */
    public function generatePayslipAction()
    {
            $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_PF"){
              $allowedAccess = 'true';
              break;
            }
          }
           if($allowedAccess == 'true'){

            return $this->render('EmployeeBundle:Payment:generate_payslip.html.twig', array(
                  'employee'=>'',
                'payment'=>'',
                'attendance'=>'',
                'errorMessage'=>'',
                'message'=>'',
                ));

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
    }
         /**
     * @Route("/auth/update-payment/{empId}",name="updatePayment")
     */
    public function updatePaymentAction($empId)
    {
            $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_PF"){
              $allowedAccess = 'true';
              break;
            }
          }

           if($allowedAccess == 'true'){
            $employee="";
            $payment="";
             $employees = $this->getDoctrine()->getRepository(Employee::class)->findBy(
                array('nID' => $empId, )
              );

              $payments = $this->getDoctrine()->getRepository(Payment::class)->findBy(
                array('employeeId' => $empId, )
              );
              foreach($employees as $empl){
              $employee = $empl;
              break;
            }
            foreach($payments as $paymt){
              $payment = $paymt;
              break;
            }

            return $this->render('EmployeeBundle:Payment:update_Payment.html.twig', array(
                  'employee'=>$employee,
                  'payment'=>$payment,
                ));

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
    }

       /**
     * @Route("/auth/getPayslipUrl/{payslipId}",name="getPaySlipUrl")
     */
    public function getPayslipUrl($payslipId)
    {
            $user = $this->getUser();
              $allowedAccess = 'false';
              $file_path ="";
             $paySlip = $this->getDoctrine()->getRepository(PaySlip::class)->find($payslipId);
             if($paySlip -> getEmployeeId() == $user->getNID()){
              $file_path = $paySlip -> getDestination();
               $allowedAccess = 'true';
            }
          
             
             
           return new JsonResponse(array(
          'filepath'=>$file_path,
          'allowed'=>$allowedAccess
                
          ));     
        
    }


       /**
     * @Route("/auth/getEmployeeDetails/{empId}",name="getEmployeeDetailPayment")
     */
    public function getEmployeeDetailPayment($empId)
    {
             $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_PF"){
              $allowedAccess = 'true';
              break;
            }
          }
          $employee ="";
          $payment="";
           $attendance="";
           $errorMessage="";
           $message="";

           if($allowedAccess == 'true'){
            try{

                 $employees = $this->getDoctrine()->getRepository(Employee::class)->findBy(
                array('nID' => $empId, )
              );
            $payments = $this->getDoctrine()->getRepository(Payment::class)->findBy(
                array('employeeId' => $empId, )
              );
             $attendances = $this->getDoctrine()->getRepository(Attendance::class)->findBy(
                array('employeeId' => $empId, )
              );
             foreach($attendances as $attnd){
              $attendance = $attnd;
              break;
            }
            foreach($employees as $empl){
              $employee = $empl;
              break;
            }
             foreach($payments as $paymt){
              $payment = $paymt;
              break;
            }


            }catch (\Exception $e){
                
               $employee ="";
          $payment="";
           $attendance="";
               $errorMessage= "Employee Data Loading Failed";
              
               }
           

            return $this->render('EmployeeBundle:Payment:generate_payslip.html.twig', array(
                'employee'=>$employee,
                'payment'=>$payment,
                'attendance'=>$attendance,
                'errorMessage'=>$errorMessage,
                'message'=>$message,
                ));

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
         
            
        
    }

    
       /**
     * @Route("/auth/generate-Payslip-submit/{empId}",name="generatePaySlipSubmit")
     */
    public function generatePayslipSubmitAction($empId)
    {

       /* check user access*/
            $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_PF"){
              $allowedAccess = 'true';
              break;
            }
          }
           /* check user access ends*/
    /*initializing variables */
          $employee ="";
          $payment="";
           $attendance="";
           $errorMessage="";
           $message="";

           /*initializing variables end*/


           if($allowedAccess == 'true'){

              try{

                /*fetching employee details*/
                 $employees = $this->getDoctrine()->getRepository(Employee::class)->findBy(
                array('nID' => $empId, )
              );
                 $payments = $this->getDoctrine()->getRepository(Payment::class)->findBy(
                array('employeeId' => $empId, )
              );
                $attendances = $this->getDoctrine()->getRepository(Attendance::class)->findBy(
                array('employeeId' => $empId, )
              );
             foreach($attendances as $attnd){
              $attendance = $attnd;
              break;
            }
            foreach($employees as $empl){
              $employee = $empl;
              break;
            }
             foreach($payments as $paymt){
              $payment = $paymt;
              break;
            }
              /*fetching employee details ends*/
             /* getting current month payslip if already generated*/

               $currentDate = new DateTime();
                $currentMonth = date_format($currentDate, 'm');
                $currentYear = date_format($currentDate, 'Y');
               $paySlipCurrent = $this->getDoctrine()->getRepository(PaySlip::class)->findBy(
                array('employeeId' => $empId,
                'month'=>$currentMonth ,
                'year' => $currentYear,
                )
              );

            foreach($paySlipCurrent as $pays){
              $payslip = $pays;
              break;
            }
            /*getting current month payslip ends*/


            if(isset($payslip)){
              $errorMessage= "Payslip for this Month has already been generated";
            }else{

               $path = $this->getParameter('kernel.root_dir') . '/web/payslips/aakash_file.pdf';
               
                  $payslip = new PaySlip();
                  $payslip->setDestination($path);
                   $payslip->setEmployeeId($empId);
                    $payslip->setMonth($currentMonth);
                    $payslip->setYear($currentYear);

                  $html = $this->renderView('EmployeeBundle:Pdf:payslip.html.twig',array(
                      'employee'=>$employee,
                      'month'=>$currentMonth,
                      'paySlip'=>$payslip,
                      'payment'=>$payment,
                'attendance'=>$attendance,
                    ));


                 

                 $this->get('knp_snappy.pdf')->generateFromHtml($html,$path);
                 $message="File has been generated";

                /* store payslip info*/
                   
                    $en = $this->getDoctrine()->getManager();
                    $en->persist($payslip);
                    $en->flush();

            }

            }catch (\Exception $e){
                
               $employee ="";
               $payment="";
               $attendance="";
               $message="";
              // $errorMessage= "Something Went wrong";
               $errorMessage= $e->getMessage();
              
               }

            return $this->render('EmployeeBundle:Payment:generate_payslip.html.twig', array(
                  'employee'=>$employee,
                'payment'=>$payment,
                'attendance'=>$attendance,
                'errorMessage'=>$errorMessage,
                  'message'=>$message,
                ));

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
    }

    

}
