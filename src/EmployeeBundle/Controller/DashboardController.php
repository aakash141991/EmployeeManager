<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\LeaveFaq;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\Announcement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DashboardController extends Controller
{
    /**
     * @Route("/auth/dashboard",name="dashboard")
     */
    public function dashboardAction()
    {	

    	$announcements = $this->getDoctrine()->getRepository(Announcement::class)->findBy(array(), array('id' => 'DESC'), 5);
        return $this->render('EmployeeBundle:Dashboard:dashboard.html.twig', array(
            'announcements' => $announcements,
        ));
    }
     /**
     * @Route("/auth/my-account",name="myAccount")
     */
    public function showAccountAction(Request $request)
    {   
          
        $user = $this->getUser();
        $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        return $this->render('EmployeeBundle:Dashboard:my_account.html.twig', array(
            'user'=>$user,
            'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }
     /**
     * @Route("/auth/change-password",name="changeAuthPassword")
     */
    public function changePasswordAction(Request $request,UserPasswordEncoderInterface $encoder)
    {   
        $user = $this->getUser();
        $employee = new Employee();
        $message="";
        $errorMessage="";
          $oldPass = $request->request->get('oldPassword');
            $newPass = $request->request->get('newPassword');
              $confirmPass = $request->request->get('confirmPassword');

              $userPass= $user->getPassword();
              /*$encodedOld = $encoder->encodePassword($employee, $oldPass);*/
             $factory = $this->get('security.encoder_factory');
    $encoderChecker = $factory->getEncoder($user);

    $bool = ($encoderChecker->isPasswordValid($user->getPassword(),$oldPass,$user->getSalt())) ? "true" : "false";
             
              if( $bool ==  "true"){
                    if($newPass == $confirmPass){
                        $encodedNew = $encoder->encodePassword($employee, $newPass);
                        $user->setPassword($encodedNew);
                          $en = $this->getDoctrine()->getManager();
                            $en->persist($user);
                            $en->flush();
                            $message="Password Changed Successfully";

                    }else{
                        $errorMessage="New Password and Confirm Password should be same";

                    }
              }else{
                 $errorMessage="Please Enter your Old password Correctly";
              }
              

        
         return $this->redirectToRoute('myAccount',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }


    /**
     * @Route("/auth/show-Faqs/{id}",name="showfaqs")
     */
    public function showFaqAction($id)
    {   
        if($id=='All'){     
         $faqs =  $this->getDoctrine()->getRepository(LeaveFaq::class)->findAll();
         $all='true';
        }else{
             $faqs =  $this->getDoctrine()->getRepository(LeaveFaq::class)->find($id);
             $all='false';
        }

          
        return $this->render('EmployeeBundle:Dashboard:faqs.html.twig', array(
            'faqs'=>$faqs,
            'all'=>$all,
        ));
    }

     /**
     * @Route("/admin/dashboard",name="admindashboard")
     */
    public function adminDashboardAction()
    {	
    	  $announcements = $this->getDoctrine()->getRepository(Announcement::class)->findBy(array(), array('id' => 'DESC'), 5);
        return $this->render('EmployeeBundle:Admin:admin-dashboard.html.twig', array(
            'announcements' => $announcements,  
        ));
    }

}
