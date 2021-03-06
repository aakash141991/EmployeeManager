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
use EmployeeBundle\Entity\ClaimedDeduction;
use EmployeeBundle\Entity\TaxSlab;
use EmployeeBundle\Entity\Attendance;
use EmployeeBundle\Entity\PaySlip;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use \DateTime;

class AdminManagePayMentController extends Controller
{



    /**
     * @Route("/admin/generate-payslip",name="adminGeneratePayslip")
     */
    public function generatePaySlipAction()
    {

       
            $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ADMIN"){
              $allowedAccess = 'true';
              break;
            }
          }
           if($allowedAccess == 'true'){
            $allEmployees=$this->getDoctrine()->getRepository(Employee::class)->findAll();

            return $this->render('EmployeeBundle:AdminCommon:generate_pay_slip.html.twig', array(
           'employee'=>'',
                'payment'=>'',
                'attendance'=>'',
                'errorMessage'=>'',
                'message'=>'',
                'allEmployees'=>$allEmployees,
        ));
           

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
  
        
    }


       /**
     * @Route("/admin/getEmployeeDetails/{empId}",name="adminGetEmployeeDetailPayment")
     */
    public function getEmployeeDetailPayment($empId,Request $request)
    {
             $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ADMIN"){
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
               $previous= $request->query->get('previous');
                $currentDate = new DateTime();
              $currentMonth = date_format($currentDate, 'm');
              $currentYear = date_format($currentDate, 'Y');


                 $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $empId, )
              );
                 if((isset($previous))&&($previous == "previous")){
              $currentMonth = $currentMonth-1;
              if($currentMonth == 0){
                 $currentMonth = 12;
                 $currentYear = $currentYear -1;

              }
            }
                 if(!isset($employee)){
                  return $this->redirectToRoute('adminGeneratePayslip');
                }else{
                   $payment = $this->getDoctrine()->getRepository(Payment::class)->findOneBy(
                array('employeeId' => $empId, )
              );
             $attendance = $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                array('employeeId' => $empId,
                'month'=>$currentMonth, 
                'year'=>$currentYear, )
              );

                }

            }catch (\Exception $e){
                
               $employee ="";
          $payment="";
           $attendance="";
               $errorMessage= $e->getMessage();
              
               }
           

            return $this->render('EmployeeBundle:AdminCommon:generate_pay_slip.html.twig', array(
                'employee'=>$employee,
                'payment'=>$payment,
                'attendance'=>$attendance,
                'errorMessage'=>$errorMessage,
                'message'=>$message,
                 'allEmployees'=>'',
                ));

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
         
            
        
    }

      /**
     * @Route("/admin/generate-Payslip-submit/{empId}",name="adminGeneratePaySlipSubmit")
     */
    public function generatePayslipSubmitAction($empId,Request $request)
    {

        /* check user access*/
            $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ADMIN"){
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
           $currentDate = new DateTime();
              $currentMonth = date_format($currentDate, 'm');
              $currentYear = date_format($currentDate, 'Y');


           if($allowedAccess == 'true'){

              try{
                $previous= $request->query->get('previous');
                /*fetching employee details*/
                  $currentDate = new DateTime();
                $currentMonth = date_format($currentDate, 'm');
                $currentYear = date_format($currentDate, 'Y');


                 if((isset($previous))&&($previous == "previous")){
              $currentMonth = $currentMonth-1;
              if($currentMonth == 0){
                 $currentMonth = 12;
                 $currentYear = $currentYear -1;

              }
            }
                 $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $empId, )
              );
                 $payment = $this->getDoctrine()->getRepository(Payment::class)->findOneBy(
                array('employeeId' => $empId, )
              );
                $attendance = $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                array('employeeId' => $empId,
                'month'=>$currentMonth,
                'year'=>$currentYear, )
              );
            
              /*fetching employee details ends*/
             /* getting current month payslip if already generated*/

             
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
             $dateObj   = DateTime::createFromFormat('!m', $currentMonth);
            $monthName = $dateObj->format('F');

            if(isset($payslip)){
              $errorMessage= "Payslip for ".$monthName." has already been generated";
            }elseif(!isset($attendance)){
                $errorMessage= "Please Update Attendance for".$monthName." before Generating payslip";
            }else{
              $filename=$empId . '_' . $currentMonth . '_' . $currentYear . 'pay.pdf';

               //$path = $this->getParameter('kernel.root_dir') . '/web/payslips/'.$filename;
               $path = $this->getParameter('kernel.root_dir') . '/../web/payslips/' . $filename;
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
                    $name = $employee->getName();
                        $subject="Pay Slip for ". $monthName ."-". $currentYear;
                      $viewPath='EmployeeBundle:MailBody:paySlip_generated.html.twig';
            
                      //$toEmail =$manager->getEmail();
                      $toEmail= $employee->getEmail();
                      $fromEmail="admin@nettantra.net";
                        
                      $email = \Swift_Message::newInstance()
                      ->setSubject($subject)
                      ->setFrom($fromEmail)
                      ->setTo($toEmail)
                      ->setBody(
                          $this->renderView(
                              $viewPath,
                              array('name' => $name,)
                          ),'text/html');
                      $email->attach(\Swift_Attachment::fromPath($path));
                       $this->get('mailer')->send($email);
                      }

            

            }catch (\Exception $e){
                
               $employee ="";
               $payment="";
               $attendance="";
               $message="";
              // $errorMessage= "Something Went wrong";
               $errorMessage= $e->getMessage();
              
               }

             return $this->render('EmployeeBundle:AdminCommon:generate_pay_slip.html.twig', array(
                  'employee'=>$employee,
                'payment'=>$payment,
                'attendance'=>$attendance,
                'errorMessage'=>$errorMessage,
                  'message'=>$message,
                  'allEmployees'=>'',
                ));

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
    }
             /**
     * @Route("/admin/update-payment/{empId}",name="adminUpdatePayment")
     */
    public function updatePaymentAction($empId)
    {
            $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ADMIN"){
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

            return $this->render('EmployeeBundle:AdminCommon:update_payment.html.twig', array(
                  'employee'=>$employee,
                  'payment'=>$payment,
                ));

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
    }

    /**
     * @Route("/admin/update-payment-submit",name="AdminUpdatePaymentSubmit")
     */
    public function updatePaymentSubmitAction(Request $request)
    {
            $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ADMIN"){
              $allowedAccess = 'true';
              break;
            }
          }

           if($allowedAccess == 'true'){
            $empId=$request->request->get('empId');
            $employee="";
            $payment="";
             $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $empId, )
              );

              $payment = $this->getDoctrine()->getRepository(Payment::class)->findOneBy(
                array('employeeId' => $empId, )
              );
              $claimed_Deductions = $this->getDoctrine()->getRepository(ClaimedDeduction::class)->findBy(
                array('employeeId' => $empId,
                  'isApproved'=>1,)
              );
              $pfRate =10;
              $claimed_Deduction = 0;
              if(isset($claimed_Deductions)){
                foreach ( $claimed_Deductions as $deduct) {
                  $claimed_Deduction =  $claimed_Deduction + $deduct->getDeduction();
                }
              }
              if(isset($payment)){
                  $isUpdate = 'true';
              }else{
                $isUpdate = 'false';
                $payment=new Payment();
              }

            /*  calculate Tax*/
              $taxSlabs = $this->getDoctrine()->getRepository(TaxSlab::class)->findAll();
              $basicPay = $request->request->get('basic_salary');
              $hra = $request->request->get('hra');
              $special_allowance = $request->request->get('special_allowance');
              $conveyance_allowance = $request->request->get('conveyance_allowance');

              $totalEarning = $basicPay + $hra + $special_allowance +$conveyance_allowance;
              $estimated_income = $totalEarning * 12;
              
              $taxableIncome = $estimated_income - $claimed_Deduction;

              $taxRate = 0;
              $cessRate =0;
              foreach ($taxSlabs as $taxSlab) {
                if($taxableIncome > $taxSlab->getIncomeFrom()){
                    if($taxSlab->getIncomeUpto() == 0){
                      $taxRate = $taxSlab->getTaxRate();
                       $cessRate=$taxSlab->getCess();
                      break;
                    }elseif($taxableIncome <= $taxSlab->getIncomeUpto()){
                      $taxRate = $taxSlab->getTaxRate();
                       $cessRate=$taxSlab->getCess();
                      break;
                    }

                  }
              }

              $it_deduction = ($taxRate /100)*$taxableIncome;

              $pf_deduction = ($pfRate/100)*$basicPay;
              
              $cess= ($cessRate /100)*$it_deduction ;

              $totalTax = $it_deduction + $cess ;
              $tdaRate= ($totalTax*100)/$taxableIncome;
              $tdaCalculated = ($tdaRate/100)*$totalEarning;
              $netSalary = $totalEarning - ($tdaCalculated+$pf_deduction);

             /**/
             $payment->setBasicSalary($basicPay);
             $payment->setHra($hra);
             $payment->setSpecialAllowance($special_allowance);
               $payment->setConveyanceAllowance($conveyance_allowance);
               $payment->setPfContribution($pf_deduction);
               $payment->setIncomeTax($tdaCalculated);
               $payment->setTotalEarning($totalEarning);
               $payment->setTotalDeduction($tdaCalculated);
               $payment->setNetSalary($netSalary);
               if($isUpdate == 'true'){
                 $en = $this->getDoctrine()->getManager();
                $en->merge($payment);
                $en->flush();
               }else{
                $en = $this->getDoctrine()->getManager();
                $en->persist($payment);
                $en->flush();
               }
              


            return $this->render('EmployeeBundle:AdminCommon:update_payment.html.twig', array(
                  'employee'=>$employee,
                  'payment'=>$payment,
                ));

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
    }

       /**
     * @Route("/admin/searchEmployeesList",name="AdminsearchEmployeesList")
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
