<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use EmployeeBundle\Entity\Employee;
use EmployeeBundle\Entity\AdminUsers;
use EmployeeBundle\Entity\ResetLink;
use EmployeeBundle\Entity\Department;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use \DateTime;

class AuthenticationController extends Controller
{


	 /**
     * @Route("/auth/login",name="login")
     */
    public function loginAction( AuthenticationUtils $authUtils,Request $request)
    {
         $error = $authUtils->getLastAuthenticationError();
        $message=$request->query->get('message');
        if(isset($message) ){
           $message =  $message;
        }else{
            $message="";
            }
          

                // last username entered by the user
                $lastUsername = $authUtils->getLastUsername();

                 $user = $this->getUser();
                 if(isset($user)){
                        return $this->redirectToRoute('dashboard',array());
                 }else{
                        return $this->render('EmployeeBundle:Authentication:login.html.twig', array(
           'last_username' => $lastUsername,
                    'error'         => $error,
                    'message'=>$message,

        ));
                 }
              
        
    }
     /**
     * @Route("/admin/login",name="adminLogin")
     */
    public function adminLoginAction( AuthenticationUtils $authUtils,Request $request)
    {
          
          $error = $authUtils->getLastAuthenticationError();
          $message=$request->query->get('message');
        if(isset($message) ){
           $message =  $message;
        }else{
            $message="";
            }
                // last username entered by the user
                $lastUsername = $authUtils->getLastUsername();

                 $user = $this->getUser();
                 if(isset($user)){
                        return $this->redirectToRoute('admindashboard',array());
                 }else{
                        return $this->render('EmployeeBundle:Authentication:admin_login.html.twig', array(
           'last_username' => $lastUsername,
                    'error'         => $error,
                    'message'=>$message,

        ));
                 }
              
        
    }

     /**
     * @Route("/auth/forgot-password",name="forgotPassword")
     */
    public function forgotPasswordAction()
    {   
        $error="";
          return $this->render('EmployeeBundle:Authentication:forgot_password.html.twig',array('error' => $error,));
    }
     /**
     * @Route("/auth/forgot-password-submit",name="forgotPasswordApply")
     */
    public function forgotPasswordSubmitAction(Request $request)
    {   
        $email=$request->request->get('email');
        

        if(isset($email)){
            $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('email' =>$email, )
              );
           $department = $this->getDoctrine()->getRepository(Department::class)->findOneBy(
                array('departmentName' =>'HR department', )
              );
           $manager = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' => $department->getDepartmentHead() )
              );
            if(isset($manager)&&isset($employee)){
                        $name = $manager->getName();
                        $subject="Password Reset";
                      $viewPath='EmployeeBundle:MailBody:forgot_pass.html.twig';
                      
                      //$toEmail =$manager->getEmail();
                      $toEmail=$manager->getEmail();
                      $fromEmail="admin@nettantra.net";
                        try{
                            $email = \Swift_Message::newInstance()
                      ->setSubject($subject)
                      ->setFrom($fromEmail)
                      ->setTo($toEmail)
                      ->setBody(
                          $this->renderView(
                              $viewPath,
                              array('name' => $name,'employee'=>$employee ,)
                          ),'text/html');
                       $this->get('mailer')->send($email);
                        $message="Reset link has been sent";

                        }catch(\Exception $e){
                             $message="something went wrong";
                        }
                      
            }else{
                $message="Employee email incorrect";
            }
                        
        }else{
           $message="";
        }
       
         return $this->redirectToRoute('login',array(
            'message' => $message,
            ));
    }

    /**
     * @Route("/admin/forgot-password",name="adminforgotPassword")
     */
    public function adminforgotPasswordAction()
    {   
        $error="";
          return $this->render('EmployeeBundle:Authentication:admin_forgot_password.html.twig',array('error' => $error,));
    }

    /**
     * @Route("/admin/forgot-password-submit",name="adminForgotPasswordApply")
     */
    public function adminForgotPasswordSubmitAction(Request $request)
    {   
        $email=$request->request->get('email');
        

        if(isset($email)){
            $admin = $this->getDoctrine()->getRepository(AdminUsers::class)->findOneBy(
                array('username' =>$email, )
              );
          $path =$request->getUri();
          $origin = $this->getOrigin( $path );

          $token = $this->getToken(50);
         $uri = $origin . "/admin/passwordReset?token=" .$token ;
          $resetlink = new ResetLink;
          $resetlink->setEmail($email);
          $resetlink->setLinkToken($token);
          $resetlink->setLinkText($uri );
          $resetlink->setCreatedAt(new DateTime());
          $resetlink->setActive(1);

          $en = $this->getDoctrine()->getManager();
                $en->persist( $resetlink);
                $en->flush();

            if(isset($admin)){
                        $name =  $admin->getName();
                        $subject="Password Reset";
                      $viewPath='EmployeeBundle:MailBody:admin_forgot_pass.html.twig';
                      
                      //$toEmail =$manager->getEmail();
                      $toEmail= $admin->getUsername();
                      $fromEmail="admin@nettantra.net";
                        try{
                            $email = \Swift_Message::newInstance()
                      ->setSubject($subject)
                      ->setFrom($fromEmail)
                      ->setTo($toEmail)
                      ->setBody(
                          $this->renderView(
                              $viewPath,
                              array('name' => $name,'resetLink'=> $resetlink)
                          ),'text/html');
                       $this->get('mailer')->send($email);
                        $message="Reset link has been sent";

                        }catch(\Exception $e){
                             $message="something went wrong";
                        }
                      
            }else{
                $message="Admin email incorrect";
            }
                        
        }else{
           $message="";
        }
       
         return $this->redirectToRoute('adminLogin',array(
            'message' => $message,
            ));
    }

 /**
     * @Route("/admin/passwordReset",name="adminResetPassFinal")
     */
    public function adminResetPassFinal(Request $request)
    {
          $token = $request->query->get('token');

           $resetLink = $this->getDoctrine()->getRepository(ResetLink::class)->findOneBy(
            array('linkToken'=>$token));


          if(isset($resetLink )){
             return $this->render('EmployeeBundle:Authentication:Reset_admin_password.html.twig',array(
            'message' => '',
            'errormessage' => '',
            'resetLink' => $resetLink,
            ));
           }else{
            return $this->render('EmployeeBundle:Error:common_error.html.twig',array(
            
            ));
           }
         
    }
    /**
     * @Route("/admin/passwordResetSubmit",name="adminResetPassFinalSubmit")
     */
    public function adminResetPassFinalSubmit(Request $request,UserPasswordEncoderInterface $encoder)
    {
          $valid=false;
          $resetId = $request->request->get('resetLinkId');
           $resetLink = $this->getDoctrine()->getRepository(ResetLInk::class)->find($resetId );
          if(isset($resetLink )){

               $newPass = $request->request->get('newPassword');
                $confirmPass=$request->request->get('confirmPassword');
                if($newPass==$confirmPass){
                  $valid=true;
                }
                if($valid){
                    $admin = $this->getDoctrine()->getRepository(AdminUsers::class)->findOneBy(
                array('username' =>$resetLink->getEmail(), )
              ); 
                    if(isset($admin)){
                      $encoded = $encoder->encodePassword($admin, $newPass);
                       $admin->setPassword($encoded);
                       $en = $this->getDoctrine()->getManager();
                            $en->merge($admin);
                            $en->flush();
                    }
                 
                }
              


               
                           
             return $this->redirectToRoute('adminLogin',array());
           
           }else{
            return $this->render('EmployeeBundle:Error:common_error.html.twig',array());
           }
         
    }

  
     /**
     * @Route("/",name="default")
     */
    public function defaultAction()
    {
          return $this->redirectToRoute('login',array(
            ));
    }

     /**
     * @Route("/auth/reset-password/{empId}",name="resetPassword")
     */
    public function resetPasswordAction($empId)
    {
           $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' =>$empId, )
              ); 
           if(isset($employee)){
             return $this->render('EmployeeBundle:Authentication:Reset_password.html.twig',array('employee' => $employee,
            'message' => '',
            'errormessage' => '',
            ));
           }else{
            return $this->redirectToRoute('dashboard',array(
            ));
           }

        
    }
     /**
     * @Route("/auth/reset-password-submit",name="resetPasswordSumit")
     */
    public function resetPasswordSubmitAction(Request $request,UserPasswordEncoderInterface $encoder)
    {   
            $empId = $request->request->get('empId');
           
           $newPass = $request->request->get('newPassword');
           $confirmPass=$request->request->get('confirmPassword');
           $message ="";
           $errormessage="";
           $user= $this->getUser();

              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_HR"){
              $allowedAccess = 'true';
              break;
            }
          }
            if($allowedAccess == 'true'){ 
                if(isset($empId) && isset($newPass) && isset($confirmPass)){
                $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(
                array('nID' =>$empId, )
              ); 
                if(isset($employee )){
                    if($confirmPass == $newPass){
                        $encoded = $encoder->encodePassword($employee, $newPass);
                         $employee->setPassword($encoded);
                         $en = $this->getDoctrine()->getManager();
                            $en->merge($employee);
                            $en->flush();
                           

                            $name = $employee->getName();
                        $subject="Password Reset successfull";
                      $viewPath='EmployeeBundle:MailBody:pass_reset_success.html.twig';
                      
                      //$toEmail =$manager->getEmail();
                      $toEmail=$employee->getEmail();
                      $fromEmail="admin@nettantra.net";
                     
                            $email = \Swift_Message::newInstance()
                      ->setSubject($subject)
                      ->setFrom($fromEmail)
                      ->setTo($toEmail)
                      ->setBody(
                          $this->renderView(
                              $viewPath,
                              array('name' => $name,'newPass'=>$newPass,)
                          ),'text/html');
                       $this->get('mailer')->send($email);
                         $message = "Password Reset successfull";
                    }else{
                        $errormessage = "New password and confirm password are different";
                    }
                      return $this->render('EmployeeBundle:Authentication:Reset_password.html.twig',array('employee' => $employee,
                            'message' => $message,
                            'errormessage' => $errormessage,
                            ));
                }
               else{
                return $this->redirectToRoute('dashboard',array());
                 $errormessage = "Please Fill all fields";
                 }

            }else{
                 return $this->redirectToRoute('dashboard',array());
                 $errormessage = "Please Fill all fields";
                 }
                 
        }else{
            return $this->redirectToRoute('accessDenied',array(
            ));
        }

           
    }


    /**
     * @Route("/auth/logout",name="logout")
     */
    public function logoutAction()
    {
        return $this->render('EmployeeBundle:Authentication:logout.html.twig', array(
            // ...
        ));
    }
    /**
     * @Route("/admin/logout",name="adminlogout")
     */
    public function adminLogoutAction()
    {
        return $this->render('EmployeeBundle:Authentication:logout.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/auth/access-denied",name="accessDenied")
     */
    public function accessDeniedAction()
    {
        return $this->render('EmployeeBundle:Authentication:accessDenied.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/admin/access-denied",name="accessDeniedAdmin")
     */
    public function accessDeniedAdminAction()
    {
        return $this->render('EmployeeBundle:Authentication:access_denied_admin.html.twig', array(
            // ...
        ));
    }

    function getToken($length){
           $token = "";
           $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
           $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
           $codeAlphabet.= "0123456789";
           $max = strlen($codeAlphabet); // edited

          for ($i=0; $i < $length; $i++) {
              $token .= $codeAlphabet[random_int(0, $max-1)];
          }

          return $token;
    }

     function getOrigin($path){
           $origin ="http://localhost:8000";


          return $origin;
    }

    

    

}
