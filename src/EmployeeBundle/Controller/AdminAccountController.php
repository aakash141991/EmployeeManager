<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use EmployeeBundle\Entity\AdminUsers;

class AdminAccountController extends Controller
{

	/**
     * @Route("/admin/my-account",name="adminMyAccount")
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
        return $this->render('EmployeeBundle:AdminAccount:my_account.html.twig', array(
            'user'=>$user,
            'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }

     /**
     * @Route("/admin/change-password",name="changeAdminPassword")
     */
    public function changePasswordAction(Request $request,UserPasswordEncoderInterface $encoder)
    {   
        $user = $this->getUser();
        $employee = new AdminUsers();
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
              

        
         return $this->redirectToRoute('adminMyAccount',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
    }
}
