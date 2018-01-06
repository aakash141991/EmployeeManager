<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\Attendance;
use EmployeeBundle\Entity\Document;
use EmployeeBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use \DateTime;

class AttendanceController extends Controller
{
    /**
     * @Route("/auth/manage-attendance",name="manageAttendance")
     */
    public function manageAttendanceAction()
    {
         $allEmployees=$this->getDoctrine()->getRepository(Employee::class)->findAll();
        return $this->render('EmployeeBundle:Attendance:manage_attendance.html.twig', array(
             'attendance'=>'',
            'employee'=>'',
            'showPrevious'=>'false',
            'allEmployees'=>$allEmployees,
        ));
    }

    /**
     * @Route("/auth/manage-attendance/{empId}",name="manageAttendanceEmployee")
     */
    public function getEmployeeDetailAttendance(Request $request,$empId)
    {

    	 $attendance="";
    	 $employee ="";
    	 $currentDate = new DateTime();
		 $currentMonth = date_format($currentDate, 'm');
		 $currentYear = date_format($currentDate, 'Y');
		 $showPrevious="false";

         $showPrevious = $request->query->get('showprevious');
          $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
		                array('nID' => $empId, )
		              );
         if(isset($showPrevious ) && $showPrevious == 'true'){

         	 $attendance = $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                array('employeeId' => $empId,
                'month'=>$currentMonth - 1 ,
                'year' => $currentYear, )
              );
         }else{
         	$showPrevious ="false";
    	   $attendance = $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                array('employeeId' => $empId,
                'month'=>$currentMonth ,
                'year' => $currentYear, )
              );
         }

    	
        return $this->render('EmployeeBundle:Attendance:manage_attendance.html.twig', array(
            'attendance'=>$attendance,
            'employee'=>$employee,
            'showPrevious'=>$showPrevious,
            'allEmployees'=>'',
        ));
    }
    /**
     * @Route("/auth/update-attendance",name="updateAttendanceEmployee")
     */
    public function UpdateAttendanceAction(Request $request)
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

          if($allowedAccess){
             $message=$request->query->get('message');
            if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        
            $empId = $request->query->get('employeeId');
              $attdId = $request->query->get('attdId');
             
              

               $currentDate = new DateTime();
                 $currentMonth = date_format($currentDate, 'm');
                 $currentYear = date_format($currentDate, 'Y');
                 $showPrevious = $request->query->get('showprevious');
                 if(!isset($showPrevious) || $showPrevious != 'true'){
                    $showPrevious="false";
                 }
                 if($showPrevious == 'true'){
                    $currentMonth=$currentMonth-1;
                    if($currentMonth < 0 ){
                        $currentMonth=12;
                        $currentYear = $currentYear-1;
                    }
                 }

            $attendance = $this->getDoctrine()->getRepository(Attendance::class)->find($attdId);
             if(!isset($attendance)){
                $attendance = $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                array('employeeId' => $empId,
                'month'=>$currentMonth ,
                'year' => $currentYear, )
              );
                
            
             }

             if(!isset($attendance)){
                $attendance = new Attendance;
             }
             
       
        


        if(isset($empId )){
             $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                        array('nID' => $empId, )
                      );
             if(isset($employee)){
                return $this->render('EmployeeBundle:Attendance:update_attendance.html.twig', array(
                          'employee'=>$employee,
                          'attendance'=>$attendance,
                          'data'=>'',
                          'showPrevious'=>$showPrevious,
                          'message'=>$message,
                            'errormessage'=>$errormessage,
                        ));
            }else{
                return $this->redirectToRoute('manageAttendance',array());
            }
            
        }else{
                return $this->redirectToRoute('manageAttendance',array());
            }
       
    }else{
        return $this->redirectToRoute('accessDenied',array());
    }

}
    /**
     * @Route("/auth/update-attendance-submit/{empId}",name="UpdateAttendanceSubmit")
     */
    public function UpdateAttendanceSubmitAction(Request $request,$empId)
    {

         //$empId = $request->request->get('empId');
        $inputValid=true;
         $showPrevious = $request->request->get('showPrevious');
         $month_selected = $request->request->get('month_selected');
         if(isset($month_selected)){
            $forDate = new DateTime($month_selected + '-01');
         $forMonth = date_format($forDate, 'm');
         $forYear = date_format($forDate, 'Y');
         }else{
            $inputValid=false;
         }
          

          
         $present = 0;
            $total = 0;
            $message="";
            $errorMessage="";
       
         if(!isset($showPrevious)){
            $showPrevious ='false';
         }

         /*try{*/

            $directory = $this->getParameter('kernel.root_dir') . '/../web/uploads/';
         $name='current_month_att.csv';
        $uploadedFile = $request->files->get('attendanceReport');
        if(isset( $uploadedFile)){
                    $file = $uploadedFile->move($directory, $name);


                if(!is_null($file)){
                    $CSVfp = fopen($file, "r");
                    $data = array();
                    while(! feof($CSVfp ))
                          {
                          array_push($data,fgetcsv($CSVfp));
                          }
                    fclose($CSVfp);
                    
                }
            foreach($data as $key => $value) {
                try{
                     $entry = $value[1];
                      if($entry == 'yes'){
                        $present= $present+1;
                        $total = $total + 1;
                    }
                    if($entry == 'no'){
                        $total = $total + 1;
                    }
                }catch(\Exception $e){
                         $errorMessage="File is corrupt";
                          $present = 0;
                            $total = 0;
                        break;
                }
               

                   
                }

                 
             if(($total > 31 )||($present >$total)||($total<28)){
                if(!isset($errorMessage)){
                     $errorMessage="Please check your inputs";
                }
               
                $inputValid=false;
             }
             if($inputValid){
                        $attendance= $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                        array('employeeId' => $empId,
                        'month'=>$forMonth,
                        'year'=> $forYear, )
                      );
                            $payment = $this->getDoctrine()->getRepository(Payment::class)->findOneBy(
                            array('employeeId' => $empId, )
                          );
                           

                            if(isset($payment)){
                                 $netSalary =  $payment->getNetSalary();
                                   if(isset($attendance))
                                        {
                                             $attendance->setMonth($forMonth);
                                            $attendance->setYear($forYear);
                                            $attendance->setTotalDays($total);
                                            $attendance->setPresentDays($present);
                                            $attendance->setAbsentDays($total - $present);
                                             $perDaySalary = $netSalary/$total;
                                             $salaryDeduction = $perDaySalary *($total - $present);
                                             $attendance->setSalaryDeducted(round($salaryDeduction,2));
                                            $en = $this->getDoctrine()->getManager();
                                            $en->merge($attendance);
                                            $en->flush();
                                            $message = "Attendance Updated Successfully";
                                        }else{
                                            $attendance = new Attendance();
                                            $attendance-> setEmployeeId($empId);
                                        $attendance->setMonth($forMonth);
                                        $attendance->setYear($forYear);
                                        $attendance->setTotalDays($total);
                                        $attendance->setPresentDays($present);
                                        $attendance->setAbsentDays($total - $present);
                                        $perDaySalary = $netSalary/$total;
                                        $salaryDeduction = $perDaySalary *($total - $present);
                                         $attendance->setSalaryDeducted(round($salaryDeduction,2));
                                        $en = $this->getDoctrine()->getManager();
                                        $en->persist($attendance);
                                        $en->flush();
                                         $message = "Attendance Updated Successfully";
                                        }
                        }else{
                                 $errorMessage="Please Update Payment Info first";
                             }

                         }


                
              
                
        }else{
            $errorMessage="File not Found";
         
        }
            
             return $this->redirectToRoute('updateAttendanceEmployee',array(
                'employeeId'=>$empId,
                'showprevious'=>'false',
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));    
         
        
    }

    /**
     * @Route("/auth/update-attendance-input/{empId}",name="UpdateAttendanceInput")
     */
    public function UpdateAttendanceInputAction(Request $request,$empId)
    {

         //$empId = $request->request->get('empId');
         $month_selected = $request->request->get('month_selected');
          $forDate = new DateTime($month_selected + '-01');
         $forMonth = date_format($forDate, 'm');
         $forYear = date_format($forDate, 'Y');
         $present =  $request->request->get('present_days');
            $total = $request->request->get('total_days');
             $errorMessage="";
             $message ="";
             $inputValid=true;
             if(($total > 31 )||($present >$total)||($total<28)){
                $errorMessage="Please check your inputs";
                $inputValid=false;
             }
             if( $inputValid) {
                    $attendance= $this->getDoctrine()->getRepository(Attendance::class)->findOneBy(
                        array('employeeId' => $empId,
                        'month'=>$forMonth,
                        'year'=> $forYear, )
                      );
                $payment = $this->getDoctrine()->getRepository(Payment::class)->findOneBy(
                array('employeeId' => $empId, )
              );
                 if(isset($payment)){
                     $netSalary =  $payment->getNetSalary();

                     if(isset($attendance))
                {
                     $attendance->setMonth($forMonth);
                    $attendance->setYear($forYear);
                    $attendance->setTotalDays($total);
                    $attendance->setPresentDays($present);
                    $attendance->setAbsentDays($total - $present);
                     $perDaySalary = $netSalary/$total;
                            $salaryDeduction = $perDaySalary *($total - $present);
                             $attendance->setSalaryDeducted(round($salaryDeduction,2));
                    $en = $this->getDoctrine()->getManager();
                    $en->merge($attendance);
                    $en->flush();
                    $message = "Attendance Updated Successfully";
                }else{
                    $attendance = new Attendance();
                    $attendance-> setEmployeeId($empId);
                $attendance->setMonth($forMonth);
                $attendance->setYear($forYear);
                $attendance->setTotalDays($total);
                $attendance->setPresentDays($present);
                $attendance->setAbsentDays($total - $present);
                 $perDaySalary = $netSalary/$total;
                            $salaryDeduction = $perDaySalary *($total - $present);
                             $attendance->setSalaryDeducted(round($salaryDeduction,2));
                $en = $this->getDoctrine()->getManager();
                $en->persist($attendance);
                $en->flush();
                $message = "Attendance Updated Successfully";
                }
             }else{
                    $errorMessage="Please Update Payment Info first";
                 }

             }
                
                
        
            
             return $this->redirectToRoute('updateAttendanceEmployee',array(
                'employeeId'=>$empId,
                'showprevious'=>'false',
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));    
         
        
    }
 /**
     * @Route("/auth/all-attendance/{empId}",name="allAttendanceData")
     */
    public function allAttendanceDataAction($empId)
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

            $employee= $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                        array('nID' => $empId,)
                      );
            $fullAttendance=$this->getDoctrine()->getRepository(Attendance::class)->findBy(
                        array('employeeId' => $empId,)
                      );
                 return $this->render('EmployeeBundle:Attendance:viewAll_attendance.html.twig', array(
                    'employee'=>$employee,
                    'fullAttendance'=>$fullAttendance,
                    ));
           }else{
                 return $this->redirectToRoute('accessDenied',array());
           }




        }
}