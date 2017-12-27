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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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

            return $this->render('EmployeeBundle:AdminCommon:generate_pay_slip.html.twig', array(
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
     * @Route("/admin/getEmployeeDetails/{empId}",name="adminGetEmployeeDetailPayment")
     */
    public function getEmployeeDetailPayment($empId)
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

                 $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $empId, )
              );
            $payment = $this->getDoctrine()->getRepository(Payment::class)->findOneBy(
                array('employeeId' => $empId, )
              );
             $attendance = $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                array('employeeId' => $empId, )
              );


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
                ));

            }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
         
            
        
    }

      /**
     * @Route("/admin/generate-Payslip-submit/{empId}",name="adminGeneratePaySlipSubmit")
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

            return $this->render('EmployeeBundle:AdminCommon:generate_pay_slip.html.twig', array(
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
