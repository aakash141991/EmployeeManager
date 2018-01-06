<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\Announcement;
use Symfony\Component\HttpFoundation\Request;


class AnnouncementsController extends Controller
{
    /**
     * @Route("/auth/announcements", name="allAnnouncements")
     */
    public function allAnnouncementAction()
    {

        $announcements = $this->getDoctrine()->getRepository(Announcement::class)->findAll();
        return $this->render('EmployeeBundle:Announcements:all_announcement.html.twig', array(
           'announcements' => $announcements,
        ));
    }

    /**
     * @Route("/admin/announcements")
     */
    public function adminAnouncementsAction()
    {
        return $this->render('EmployeeBundle:Announcements:admin_anouncements.html.twig', array(
            // ...
        ));
    }

     /**
     * @Route("/auth/edit-announcement/{announceId}")
     */
    public function editAnnouncementAction($announceId)
    {

        $announcement = $this->getDoctrine()->getRepository(Announcement::class)->find($announceId);
        return $this->render('EmployeeBundle:Announcements:edit_announcement.html.twig', array(
           'announcement' => $announcement,
        ));
    }


     /**
     * @Route("/auth/edit-announcement-submit" , name="editAnnouncementSubmit")
     */
    public function editAnnouncementSubmitAction( Request $request)
    {

        $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ATTENDANCE_MANAGER"){
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

                return $this->redirectToRoute('allAnnouncements',array());
          }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
    }


     /**
     * @Route("/auth/delete-announcement/{announceId}",name="deleteAnnouncementSubmit")
     */
    public function deleteAnnouncementAction( $announceId)
    {

        $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ATTENDANCE_MANAGER"){
              $allowedAccess = 'true';
              break;
            }
          }
          if($allowedAccess == 'true'){
                
                $announcement = $this->getDoctrine()->getRepository(Announcement::class)->find($announceId);

                 $en = $this->getDoctrine()->getManager();
                $en->remove($announcement);
                $en->flush();

                return $this->redirectToRoute('allAnnouncements',array());
          }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
    }

     /**
     * @Route("/auth/add-Announcement")
     */
    public function addAnnouncementAction()
    {

        return $this->render('EmployeeBundle:Announcements:add_announcement.html.twig', array(
           
        ));
    }
    
    /**
     * @Route("/auth/add-announcement-submit" , name="addAnnouncementSubmit")
     */
    public function addAnnouncementSubmitAction( Request $request)
    {

        $user = $this->getUser();
              $allowedAccess = 'false';
              $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ATTENDANCE_MANAGER"){
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

                return $this->redirectToRoute('allAnnouncements',array());
          }else{
                    return $this->redirectToRoute('accessDenied',array());
            }
        
    }


}
