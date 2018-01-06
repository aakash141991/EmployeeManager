<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\ClaimedDeduction;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;

class DeductionClaimController extends Controller
{
    /**
     * @Route("/auth/deduction-claim",name="deductionClaim")
     */
    public function claimDeductionAction()
    {
    	$user = $this->getUser();
    	$deductions = $this->getDoctrine()->getRepository(ClaimedDeduction::class)->findBy(
                array('employeeId' => $user->getNID(), )
              );
        return $this->render('EmployeeBundle:DeductionClaim:claim_deduction.html.twig', array(
            'deductions'=>$deductions, 
        ));
    }
    /**
     * @Route("/auth/new-deduction-claim/{empId}",name="newDeductionClaim")
     */
    public function addNewDeductionAction($empId)
    {
    	
        return $this->render('EmployeeBundle:DeductionClaim:claim_new_deduction.html.twig', array(
          
        ));
    }

    /**
     * @Route("/auth/new-deduction-claim-submit",name="newDeductionClaimSubmit")
     */
    public function addNewDeductionSubmitAction(Request $request)
    {
    	$user = $this->getUser();
    	$empId = $user->getNID();
    	   $deduction_amount=$request->request->get('deduction_amount');
    	   $deduction = new ClaimedDeduction();
    	   $deduction->setEmployeeId($empId);
    	   $deduction->setDeduction($deduction_amount);
    	    $deduction->setIsApproved(0);
    	  
    	 $currentDate = new DateTime();
		 $currentMonth = date_format($currentDate, 'm');
		 $currentYear = date_format($currentDate, 'Y');
		 if($currentMonth <= 3){
		 	$currentYear = $currentYear-1;
		 }
		  $deduction->setYear($currentYear);
		   $en = $this->getDoctrine()->getManager();
                $en->persist($deduction);
                $en->flush();
          return $this->redirectToRoute('deductionClaim',array(
                
            ));    
         
        
    }

        /**
     * @Route("/auth/respond-deduction-claim",name="respondDeduction")
     */
    public function respondClaimsAction(Request $request)
    {
        $user = $this->getUser();
        $deductionClaims = $this->getDoctrine()->getRepository(ClaimedDeduction::class)->findBy(
                array('assignedTo' => $user->getNID(),
                'isApproved'=> 0, )
              );
         return $this->render('EmployeeBundle:DeductionClaim:respond_claim.html.twig', array(
          'deductionClaims' =>$deductionClaims,
        ));
         
        
    }
    
    

}
