<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Announcement;
use Symfony\Component\HttpFoundation\Request;

class AdminManageAnnouncementController extends Controller
{
    /**
     * @Route("/admin/manage-announcements",name="AdminAllAnnouncemnets")
     */
    public function allAnnouncementAction()
    {

    	$announcements = $this->getDoctrine()->getRepository(Announcement::class)->findAll();
       
        return $this->render('EmployeeBundle:AdminManageAnnouncement:all_announcement.html.twig', array(
            'announcements' => $announcements,
        ));
    }

     /**
     * @Route("/admin/edit-announcement/{announceId}")
     */
    public function editAnnouncementAction($announceId)
    {

        $announcement = $this->getDoctrine()->getRepository(Announcement::class)->find($announceId);
        return $this->render('EmployeeBundle:AdminManageAnnouncement:edit_announcement.html.twig', array(
           'announcement' => $announcement,
        ));
    }

    /**
     * @Route("/admin/edit-announcement-submit" , name="adminEditAnnouncementSubmit")
     */
    public function editAnnouncementSubmitAction( Request $request)
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
                $announceId = $request->request->get('announceId');
            $title= $request->request->get('title');
            $description = $request->request->get('description');
                $announcement = $this->getDoctrine()->getRepository(Announcement::class)->find($announceId);
                $announcement->setTitle($title);
                $announcement->setDescription($description);

                 $en = $this->getDoctrine()->getManager();
                $en->merge($announcement);
                $en->flush();

                return $this->redirectToRoute('AdminAllAnnouncemnets',array());
          }else{
                    return $this->redirectToRoute('adminAccessDenied',array());
            }
        
    }

     /**
     * @Route("/admin/add-Announcement")
     */
    public function addAnnouncementAction()
    {

        return $this->render('EmployeeBundle:AdminManageAnnouncement:add_announcement.html.twig', array(
           
        ));
    }

    /**
     * @Route("/admin/add-announcement-submit" , name="adminAddAnnouncementSubmit")
     */
    public function addAnnouncementSubmitAction( Request $request)
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
               
            $title= $request->request->get('title');
            $description = $request->request->get('description');
                $announcement = new Announcement;
                $announcement->setTitle($title);
                $announcement->setDescription($description);

                 $en = $this->getDoctrine()->getManager();
                $en->persist($announcement);
                $en->flush();

                return $this->redirectToRoute('AdminAllAnnouncemnets',array());
          }else{
                    return $this->redirectToRoute('adminAccessDenied',array());
            }
        
    }

      /**
     * @Route("/admin/delete-announcement-confirm/{announceId}" ,name="adminDeleteAnnouncement")
     */
    public function deleteAnnouncementAction( $announceId)
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
                
                $announcement = $this->getDoctrine()->getRepository(Announcement::class)->find($announceId);

                 $en = $this->getDoctrine()->getManager();
                $en->remove($announcement);
                $en->flush();

                return $this->redirectToRoute('AdminAllAnnouncemnets',array());
          }else{
                    return $this->redirectToRoute('adminAccessDenied',array());
            }
        
    }


}
