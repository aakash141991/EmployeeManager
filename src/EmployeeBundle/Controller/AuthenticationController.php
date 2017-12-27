<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends Controller
{


	 /**
     * @Route("/auth/login",name="login")
     */
    public function loginAction( AuthenticationUtils $authUtils)
    {
          $error = $authUtils->getLastAuthenticationError();

                // last username entered by the user
                $lastUsername = $authUtils->getLastUsername();

                 $user = $this->getUser();
                 if(isset($user)){
                        return $this->redirectToRoute('dashboard',array());
                 }else{
                        return $this->render('EmployeeBundle:Authentication:login.html.twig', array(
           'last_username' => $lastUsername,
                    'error'         => $error,

        ));
                 }
              
        
    }
     /**
     * @Route("/admin/login",name="adminLogin")
     */
    public function adminLoginAction( AuthenticationUtils $authUtils)
    {
          $error = $authUtils->getLastAuthenticationError();

                // last username entered by the user
                $lastUsername = $authUtils->getLastUsername();

                 $user = $this->getUser();
                 if(isset($user)){
                        return $this->redirectToRoute('admindashboard',array());
                 }else{
                        return $this->render('EmployeeBundle:Authentication:admin_login.html.twig', array(
           'last_username' => $lastUsername,
                    'error'         => $error,

        ));
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

    

    

}
