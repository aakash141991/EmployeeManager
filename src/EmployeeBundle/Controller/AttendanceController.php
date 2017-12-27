<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\Attendance;
use EmployeeBundle\Entity\Document;
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

        return $this->render('EmployeeBundle:Attendance:manage_attendance.html.twig', array(
             'attendance'=>'',
            'employee'=>'',
            'showPrevious'=>'false',
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
        ));
    }
    /**
     * @Route("/auth/update-attendance",name="updateAttendanceEmployee")
     */
    public function UpdateAttendanceAction(Request $request)
    {
        $empId = $request->query->get('employeeId');
        $showPrevious = $request->query->get('showprevious');
         $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                        array('nID' => $empId, )
                      );
        if($showPrevious == 'true'){
            
        }else{
           
        }
        $document = new Document();
        $form=$this->createFormBuilder($document)
        ->add('file',FileType::class, array('attr' => array('class' => 'fieldClass')))
        ->getForm();
       

        return $this->render('EmployeeBundle:Attendance:update_attendance.html.twig', array(
          'employee'=>$employee,
          'form' => $form->createView(),
          'data'=>'',
          'showPrevious'=>$showPrevious,
        ));
    }
    /**
     * @Route("/auth/update-attendance-submit/{empId}",name="UpdateAttendanceSubmit")
     */
    public function UpdateAttendanceSubmitAction(Request $request,$empId)
    {

         //$empId = $request->request->get('empId');
         $showPrevious = $request->request->get('showPrevious');
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
        }else{

         
        }
            $present = 0;
            $total = 0;
            foreach($data as $entry) {
                $total = $total + 1;
                foreach ($entry as $key => $value) {
                    if($value == 'Date'){
                        continue;
                    }else{
                        if($value == 'yes'){
                             $present= $present+1;
                        }
                    }
                }
            }
         return $this->render('EmployeeBundle:Attendance:show_attendance.html.twig', array(
          'data'=> $data,
         
        ));

         /*}catch (\Exception $e){
                
                return $this->redirectToRoute('manageAttendance',array(
            
            ));
             }*/
        
              
              
         
        
    }


}
