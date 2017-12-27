<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\EmployeeAsset;
use EmployeeBundle\Entity\AssetTypes;
use EmployeeBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;

class AssetController extends Controller
{
    /**
     * @Route("/auth/allAssets",name="allAssets")
     */
    public function allAssetsAction()
    {
    	$assets = $this->getDoctrine()->getRepository(EmployeeAsset::class)->findBy(
                array('isAssigned' => 1, )
              );;
        return $this->render('EmployeeBundle:Asset:all_assets.html.twig', array(
            'assets'=>$assets,
        ));
    }
    /**
     * @Route("/auth/getAssetDetails/{id}",name="assetDetails")
     */
    public function getAssetDetailsAction($id)
    {
    	$assetDetails = $this->getDoctrine()->getRepository(AssetTypes::class)->find($id);
    		$description= $assetDetails->getDescription();
    		$type=$assetDetails->getTypeName();

    	
     		return new JsonResponse(array(
     			'assetDescription' => $description,
     			'type'=>$type,
                
     			));
    }
    /**
     * @Route("/auth/applyAsset",name="applyAsset",)
     */
    public function applyAssetAction(Request $request)
    {
          $user = $this->getUser();
          $assetTypes=$this->getDoctrine()->getRepository(AssetTypes::class)->findAll();
          $assetRequests=$this->getDoctrine()->getRepository(EmployeeAsset::class)->findAll();
          $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
         return $this->render('EmployeeBundle:Asset:apply_asset.html.twig', array(
            'user'=>$user,
            'assetTypes'=>$assetTypes,
            'assetRequests'=>$assetRequests,
            'message'=>$message,
                'errormessage'=>$errormessage,
        ));
        
    }
     /**
     * @Route("/auth/applyAssetRequest",name="applyAssetRequest",methods={"POST"})
     */
    public function applyAssetSubmitAction(Request $request)
    {   
      try{
          $user = $this->getUser();
          $message="";
          $errorMessage="";
          $assetTypeId=$request->request->get('asset_type');
          $assetType=$this->getDoctrine()->getRepository(AssetTypes::class)->find($assetTypeId);
          
          $employeeAsset = new EmployeeAsset();
          $employeeAsset->setAssetTypeId($assetTypeId);
          $employeeAsset->setEmployeeId($user->getNID());
          $employeeAsset->setEmployeeName($user->getName());

          if(isset($assetType)){
             $employeeAsset->setAssetType($assetType->getTypeName());
              $employeeAsset->setIsAssigned(0);
                $employeeAsset->setIsRequested(1);
                $employeeAsset->setIsRejected(0);
                $employeeAsset->setAssetId('');
                $employeeAsset->setFromDate('');
                $en = $this->getDoctrine()->getManager();
                $en->persist($employeeAsset);
                $en->flush();
                $message="New Asset applied successfully !";
                }else{
                  $errorMessage="something went wrong. Please Apply again !";
                }
               
         
              }catch (\Exception $e){
                
                $message="";
               $errorMessage= "something went wrong. Please Apply again !";
              
               }

         return $this->redirectToRoute('applyAsset',array(
                'message'=>$message,
                'errormessage'=>$errorMessage,
            ));
        
    }

         /**
     * @Route("/auth/respond-asset-request",name="respondAssetRequest",methods={"GET"})
     */
    public function respondAssetAction(Request $request)
    {
            $allowedAccess = 'false';
          $user = $this->getUser();
          $roles= $user->getRoles();
          foreach($roles as $role){
            if($role == "ROLE_ASSET_MANAGER"){
              $allowedAccess = 'true';
              break;
            }
          }
          if($allowedAccess == 'true'){
                  $assetRequests = $this->getDoctrine()->getRepository(EmployeeAsset::class)->findBy(
                array(
                  'isRequested' => 1,
                 ));


         return $this->render('EmployeeBundle:Asset:respond_asset.html.twig', array(
            'assetRequests'=>$assetRequests,
        ));
          }else{
              return $this->redirectToRoute('accessDenied',array(
            ));
          }
        
        
    }

    
    /**
     * @Route("/auth/respond-asset-submit/{assetId}",name="respondAssetSubmit",methods={"GET"})
     */
        public function respondAssetSubmitAction(Request $request,$assetId)
        {
          $resp = $request->query->get('resp');
          $asset = $this->getDoctrine()->getRepository(EmployeeAsset::class)->find($assetId);
          $employeeId = $asset->getEmployeeId();
          
          $employees=$this->getDoctrine()->getRepository(Employee::class)->findBy(
                array(
                  'nID' => $employeeId,
                 ));
          foreach($employees as $employee){
                        $toEmail=$employee->getEmail();
                        break;
                      }
            $name=$asset->getEmployeeName();
            $fromEmail='admin@nettantra.net';

          if($resp == 'yes'){
                    $asset->setIsAssigned(1);
                    $asset->setIsRequested(0);
                    $date = new DateTime();
                    $dt = $date->format('d/m/Y');
                    $asset->setFromDate($dt);

                      $subject="Asset Request accepted";
                      $viewPath='EmployeeBundle:MailBody:asset_accepted.html.twig';

          }elseif( $resp =='no'){
              $asset->setIsRejected(1);
              $asset->setIsRequested(0);
              $subject="Asset Request Rejected";
              $viewPath='EmployeeBundle:MailBody:asset_rejected.html.twig';
          }
          $en = $this->getDoctrine()->getManager();
                $en->merge($asset);
                $en->flush();

                 /* Send Mail*/
                $email = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody(
                    $this->renderView(
                        $viewPath,
                        array('name' => $name,'assetId'=>$assetId)
                    ),'text/html');
                 $this->get('mailer')->send($email);

          return new JsonResponse(array(
          'data'=>'true',
                
          ));
          
          }



}
