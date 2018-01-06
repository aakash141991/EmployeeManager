<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\SalaryAccount;
use EmployeeBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;

class SalaryAccountController extends Controller
{
    /**
     * @Route("/auth/all-salary-accounts",name="allSalaryAccounts")
     */
    public function allSalaryAccountAction(Request $request)
    {

    	$message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
    	$accounts = $this->getDoctrine()->getRepository(SalaryAccount::class)->findAll();
        return $this->render('EmployeeBundle:SalaryAccount:all_salary_account.html.twig', array(
           'accounts' => $accounts,
           'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }

    /**
     * @Route("/auth/add-new-salaryAccount",name="newSalaryAccount")
     */
    public function addSalaryAccountAction(Request $request)
    {
    	$message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
    	$allEmployees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        return $this->render('EmployeeBundle:SalaryAccount:add_salary_account.html.twig', array(
          	'allEmployees' => $allEmployees,
          	'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }
    /**
     * @Route("/auth/add-new-salaryAccount-submit",name="addSalaryAccountSubmit")
     */
    public function addSalaryAccountSubmitAction(Request $request)
    {
    	$valid='false';
    	$message="";
    	$errorMessage = "";
    	$empId = $request->request->get('empId');
    	$employee= $this->getDoctrine()->getRepository(Employee::class)->findOneBy(array('nID'=>$empId,) );
    	$acNo = $request->request->get('acNo');
    	$confirmAcNo = $request->request->get('confirmAcNo');
    	$bankName = $request->request->get('confirmAcNo');
    	$ifscCode = $request->request->get('ifscCode');
    	if(isset($acNo) && isset($confirmAcNo) && isset($bankName) && isset($ifscCode)&&isset($empId)){

    		if($confirmAcNo == $acNo){
    			$valid='true';
    		}else{
    			$errorMessage ="Account Number and confirm Account number are different.";
    		}
    		
    	}else{
    		$errorMessage ="Please fill all fields" ;
    	}
    	if($valid == 'true'){
    				if(!isset($employee)){
    					$valid='false';
			    		$errorMessage ="No Employee Found with given ID" ;
			    	}else{
			    		$salaryAc= $this->getDoctrine()->getRepository(SalaryAccount::class)->findOneBy(array('employeeId' => $employee->getNID()) );
			    		if(isset($salaryAc)){
							$valid='false';
							$errorMessage ="Salary Account of " .  $employee->getName() . " already exists, please update the same"  ;
			    		}
			    	}
    	}
    	



    	if($valid == 'true'){
    		$salaryAccount = new SalaryAccount;
    		$salaryAccount->setBankName($bankName);
    		$salaryAccount->setIfscCode($ifscCode);
    		$salaryAccount->setAccountNumber($acNo);
    		$salaryAccount->setEmployeeName($employee->getName());
    		$salaryAccount->setEmployeeId($empId);

    		$en = $this->getDoctrine()->getManager();
                $en->persist($salaryAccount);
                $en->flush();
                $message="Salary account of " . $employee->getName() ." added Successfully";

    	}
        return $this->redirectToRoute('newSalaryAccount',array(
               'message'=>$message,
               'errormessage'=>$errorMessage,
            ));
    }

   
    /**
     * @Route("/auth/edit-salaryAccount",name="editSalaryAccount")
     */
    public function editSalaryAccountAction(Request $request)
    {
    	$message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errormessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
            $allEmployees="";
             $salaryAc="";
            $salaryAcId = $request->query->get('salaryAcId');
            if(isset($salaryAcId)){
            	$salaryAc = $this->getDoctrine()->getRepository(SalaryAccount::class)->find($salaryAcId);
    			
            }else{
            	$salaryAc=new SalaryAccount;
            }
            $allEmployees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        return $this->render('EmployeeBundle:SalaryAccount:edit_salary_account.html.twig', array(
          	'allEmployees' => $allEmployees,
          	'salaryAc' => $salaryAc,
          	'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }
    

     /**
     * @Route("/auth/edit-salaryAccount-submit",name="editSalaryAccountSubmit")
     */
    public function editSalaryAccountSubmitAction(Request $request)
    {
    	$valid='false';
    	$message="";
    	$errorMessage = "";
    	$empId = $request->request->get('empId');
    	$employee= $this->getDoctrine()->getRepository(Employee::class)->findOneBy(array('nID'=>$empId,) );
    	$acNo = $request->request->get('acNo');
    	$confirmAcNo = $request->request->get('confirmAcNo');
    	$bankName = $request->request->get('confirmAcNo');
    	$ifscCode = $request->request->get('ifscCode');
    	$salaryAcId = $request->request->get('salaryAcId');
    	if(isset($acNo) && isset($confirmAcNo) && isset($bankName) && isset($ifscCode)&&isset($empId)&& isset($salaryAcId)){

    		if($confirmAcNo == $acNo){
    			$valid='true';
    		}else{
    			$errorMessage ="Account Number and confirm Account number are different.";
    		}
    		
    	}else{
    		$errorMessage ="Please fill all fields" ;
    	}
    	if($valid == 'true'){
    				if(!isset($employee)){
    					$valid='false';
			    		$errorMessage ="No Employee Found with given ID" ;
			    	}else{
			    		$salaryAc= $this->getDoctrine()->getRepository(SalaryAccount::class)->findOneBy(array('employeeId' => $employee->getNID()) );
			    		if(!isset($salaryAc)){
							$valid='false';
							$errorMessage ="Salary Account of " .  $employee->getName() . " dosen't exist, please add the same"  ;
			    		}
			    	}
    	}
    	



    	if($valid == 'true'){
    		$salaryAccount = $this->getDoctrine()->getRepository(SalaryAccount::class)->findOneBy(array('employeeId' => $employee->getNID()) );
    		$salaryAccount->setBankName($bankName);
    		$salaryAccount->setIfscCode($ifscCode);
    		$salaryAccount->setAccountNumber($acNo);
    		$salaryAccount->setEmployeeName($employee->getName());
    		$salaryAccount->setEmployeeId($empId);

    		$en = $this->getDoctrine()->getManager();
                $en->persist($salaryAccount);
                $en->flush();
                $message="Salary account of " . $employee->getName() ." updated Successfully";

    	}
        return $this->redirectToRoute('editSalaryAccount',array(
               'message'=>$message,
               'errormessage'=>$errorMessage,
               'salaryAcId'=>$salaryAcId,
            ));
    }



/**
     * @Route("/auth/delete-account-details-confirm/{salaryAcId}",name="deleteAccountDetails")
     */
    public function deleteAccountDetailsAction($salaryAcId)
    {

    	$result='false';
    	$salaryAc = $this->getDoctrine()->getRepository(SalaryAccount::class)->find($salaryAcId);
    	if(isset($salaryAc)){
    		$en = $this->getDoctrine()->getManager();
                $en->remove($salaryAc);
                $en->flush();
                $result='true';
    	}
    	return new JsonResponse(array(
          'data'=>$result,
                
          ));
        
    }

}
