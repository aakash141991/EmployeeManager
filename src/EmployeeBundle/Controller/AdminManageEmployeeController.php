<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\Attendance;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;
use EmployeeBundle\Entity\Payment;
use EmployeeBundle\Entity\Roles;
use EmployeeBundle\Entity\Designation;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class AdminManageEmployeeController extends Controller
{


	/**
     * @Route("/admin/manage-attendance",name="adminManageAttendance")
     */
    public function manageAttendanceAction()
    {

                  $allEmployees=$this->getDoctrine()->getRepository(Employee::class)->findAll();

        return $this->render('EmployeeBundle:AdminEmployeeManage:manage_attendance.html.twig', array(
             'attendance'=>'',
            'employee'=>'',
            'showPrevious'=>'false',
            'allEmployees'=>$allEmployees,
        ));
    }

    /**
     * @Route("/admin/manage-attendance/{empId}",name="adminGetEmployeeAttendance")
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

    	
        return $this->render('EmployeeBundle:AdminEmployeeManage:manage_attendance.html.twig', array(
            'attendance'=>$attendance,
            'employee'=>$employee,
            'showPrevious'=>$showPrevious,
             'allEmployees'=>'',
        ));
    }

     /**
     * @Route("/admin/update-attendance",name="AdminUpdateAttendanceEmployee")
     */
    public function UpdateAttendanceAction(Request $request)
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
                return $this->render('EmployeeBundle:AdminEmployeeManage:update_attendance.html.twig', array(
                          'employee'=>$employee,
                          'attendance'=>$attendance,
                          'data'=>'',
                          'showPrevious'=>$showPrevious,
                          'message'=>$message,
                            'errormessage'=>$errormessage,
                        ));
            }else{
                return $this->redirectToRoute('adminManageAttendance',array());
            }
            
        }else{
                return $this->redirectToRoute('adminManageAttendance',array());
            }
         
      }else{
          return $this->redirectToRoute('adminAccessDenied',array());
      }
    }


     /**
     * @Route("/admin/add-new-employee",name="AdminAddNewEmployee")
     */
    public function addNewEmployeeAction(Request $request)
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
          $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
          if($allowedAccess == 'true'){ 
            $allEmployees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
            $designations = $this->getDoctrine()->getRepository(Designation::class)->findAll();
            $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();

            $em = $this->getDoctrine()->getManager();

                 $query = $em->createQuery("SELECT MAX(o.nID) as maxColumn FROM EmployeeBundle:Employee o ");
                  $results = $query->getResult();
                  foreach($results as $result){
                    $newEmpId =  ++$result['maxColumn'];
                    break;
                  }


                  
             return $this->render('EmployeeBundle:AdminEmployeeManage:add_new_employee.html.twig', array(
                        'designations'=>$designations,
                        'newEmpId'=>$newEmpId,
                        'roles'=>$roles,
                        'allEmployees'=>$allEmployees,
                        'message'=>$message,
                'errormessage'=>$errormessage,
                    ));
          }else{
             return $this->redirectToRoute('adminAccessDenied',array(
            ));
          }
    



                  
                    
          
    


           
    }

    /**
     * @Route("/admin/new-employee-submit",name="AdminSubmitNewEmployee",methods={"POST"})
     */
    public function submitNewEmployeeAction(Request $request ,UserPasswordEncoderInterface $encoder)
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


          $valid= true;
          $message="";
          $errorMessage="";
          $managerNid = $request->request->get('managerNid');
          $empId = $request->request->get('employeeId');
          $name=$request->request->get('name');
          $email= $request->request->get('email');
          $phone= $request->request->get('phone');
          $panNo=$request->request->get('panNumber');
          $desgId = $request->request->get('designation');
          $gender = $request->request->get('gender');
          $doj = new DateTime($request->request->get('doj'));
          $dob = new DateTime($request->request->get('dob'));
          $currentDate = new DateTime();
          $rolesAll= array();
              $rolesArray = $request->request->get('role');

             /* validation starts*/
          if(isset($managerNid )&& isset($empId )&&isset($name)&&isset($email)&&isset($phone)&&isset($panNo)&&isset($desgId)&&isset($doj)&&isset($dob) && isset($gender)&&isset($rolesArray)){

             $manager =  $this->getDoctrine()->getRepository(Employee::class)->findOneBy(array('nID' => $managerNid ) );
             $designation = $this->getDoctrine()->getRepository(Designation::class)->find($desgId );
             $availableEmp =  $this->getDoctrine()->getRepository(Employee::class)->findOneBy(array('nID' => $empId) );

                if(!isset( $manager )){
                   $valid= false;
                   $errorMessage ="Please recheck Manager Id Provided";
                 }elseif(!isset($designation)){
                  $valid= false;
                    $errorMessage ="Please recheck Designation Provided";
                 }elseif(isset($availableEmp)){
                  $valid= false;
                    $errorMessage ="Employee Id provided already exists ";
                 }

                 $empAge =($currentDate->diff($dob )->days)/365;
                 if($empAge < 18){
                   $valid= false;
                    $errorMessage ="Employee must be atleast 18 years Old ";
                 }elseif($doj < $dob){
                   $valid= false;
                    $errorMessage ="Date Of joining is not correct";
                 }






          }else{
            $errorMessage ="Please fill all fields";
             $valid= false;
          }
          /* validation ends*/

          if($allowedAccess == 'true'){ 


             if($valid){
                  $employee= new Employee();
              $employee->setNID( $empId);
               $employee->setName($name);
               $employee->setEmail($email);
              $employee->setUsername($email);
              $employee->setPhone($phone);
              
               $employee->setManagerNid($managerNid);
               $employee->setAddress($request->request->get('address'));     
               $employee->setPanNumber($panNo);
               $employee->setDesignation($designation);
               $employee->setEmployeeStatus('active');
              $employee->setDateOfJoining($doj );
              $employee->setGender($gender);
              $employee->setDateOfBirth($dob);

              $encoded = $encoder->encodePassword($employee, 'Welcome');
              $employee->setPassword($encoded);

              
              foreach ($rolesArray as $role) {
               array_push($rolesAll,$role);
              }

           
             $employee-> setRoles($rolesAll);
            

              $en = $this->getDoctrine()->getManager();
                $en->persist($employee);
                $en->flush();
                $message="Employee Added Successfully";
             }
            
                return $this->redirectToRoute('AdminAddNewEmployee',array(
                  'message'=>$message,
                'errormessage'=>$errorMessage,
            ));

          }else{
             return $this->redirectToRoute('adminAccessDenied',array(
            ));
          }
           
      }

       /**
     * @Route("/admin/update-employee",name="AdminUpdateEmployee")
     */
    public function updateEmployeeAction()
    {
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        return $this->render('EmployeeBundle:AdminEmployeeManage:update_employee.html.twig', array(
            'employees'=>$employees,
        ));
    }
     /**
     * @Route("/admin/update-employee/{empId}",name="adminUpdateEmployeeWithId")
     */
    public function updateEmployeeWithIdAction($empId)
    {
        $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $empId, )
              );
        $designations = $this->getDoctrine()->getRepository(Designation::class)->findAll();
        $roles = $this->getDoctrine()->getRepository(Roles::class)->findAll();

        $allEmployees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        return $this->render('EmployeeBundle:AdminEmployeeManage:update_employee_single.html.twig', array(
            'employee'=>$employee,
            'designations'=>$designations,
                        'roles'=>$roles,
                        'allEmployees'=> $allEmployees,
        ));
    }
      /**
     * @Route("/admin/updateEmployeeSubmit",name="adminUpdateEmployeeSubmit",methods={"POST"})
     */
    public function updateEmployeeSubmitAction(Request $request,UserPasswordEncoderInterface $encoder)
    {
      $empId=  $request->request->get('employeeId');
        $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $empId, )
              );
         $employee->setNID( $request->request->get('employeeId'));
               $employee->setName($request->request->get('name'));
               $employee->setEmail($request->request->get('email'));
              $employee->setUsername($request->request->get('email'));
              $employee->setPhone($request->request->get('phone'));
              
               $employee->setManagerNid($request->request->get('managerNid'));
               $employee->setAddress($request->request->get('address'));     
               $employee->setPanNumber($request->request->get('panNumber'));
                $desgId = $request->request->get('designation');
               $designation = $this->getDoctrine()->getRepository(Designation::class)->find($desgId );
               $employee->setDesignation($designation);
               $employee->setEmployeeStatus($request->request->get('active'));
              $employee->setDateOfJoining(new DateTime($request->request->get('doj')));
              $employee->setGender($request->request->get('gender'));
              $employee->setDateOfBirth(new DateTime($request->request->get('dob')));

              /*$encoded = $encoder->encodePassword($employee, 'Welcome');
              $employee->setPassword($encoded);
*/
              $rolesAll= array();
              $rolesArray = $request->request->get('role');
              foreach ($rolesArray as $role) {
               array_push($rolesAll,$role);
              }

             $employee-> setRoles($rolesAll);
            

              $en = $this->getDoctrine()->getManager();
                $en->merge($employee);
                $en->flush();
        return $this->redirectToRoute('AdminUpdateEmployee',array(
            ));


       
    }

        /**
     * @Route("/admin/update-attendance-submit/{empId}",name="AdminUpdateAttendanceSubmit")
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
                $errorMessage="Please check your inputs";
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
            
             return $this->redirectToRoute('AdminUpdateAttendanceEmployee',array(
                'employeeId'=>$empId,
                'showprevious'=>'false',
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));    
         
        
    }

    /**
     * @Route("/admin/update-attendance-input/{empId}",name="AdminUpdateAttendanceInput")
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
               

        
            
             return $this->redirectToRoute('AdminUpdateAttendanceEmployee',array(
                'employeeId'=>$empId,
                'showprevious'=>'false',
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));    
         
        
    }


     /**
     * @Route("/admin/all-attendance/{empId}",name="adminSeeallAttendanceData")
     */
    public function allAttendanceDataAction($empId)
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

            $employee= $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                        array('nID' => $empId,)
                      );
            $fullAttendance=$this->getDoctrine()->getRepository(Attendance::class)->findBy(
                        array('employeeId' => $empId,)
                      );
                 return $this->render('EmployeeBundle:AdminEmployeeManage:viewAll_attendance.html.twig', array(
                    'employee'=>$employee,
                    'fullAttendance'=>$fullAttendance,
                    ));
           }else{
                 return $this->redirectToRoute('accessDenied',array());
           }




        }

}
