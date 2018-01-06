<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmployeeBundle\Entity\SalaryAccount;
use EmployeeBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;

class AdminManageSalaryAccountsController extends Controller
{


    /**
     * @Route("/admin/all-salary-account",name="AdminAllSalaryAccounts")
     */
    public function allSalaryAccountsAction(Request $request)
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

    	
        return $this->render('EmployeeBundle:AdminManageSalaryAccount:all_salary_account.html.twig', array(
             'accounts' => $accounts,
           'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }

     /**
     * @Route("/admin/add-new-salaryAccount",name="AdminNewSalaryAccount")
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
        return $this->render('EmployeeBundle:AdminManageSalaryAccount:add_salary_account.html.twig', array(
          	'allEmployees' => $allEmployees,
          	'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }

    /**
     * @Route("/admin/add-new-salaryAccount-submit",name="AdminAddSalaryAccountSubmit")
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
        return $this->redirectToRoute('AdminNewSalaryAccount',array(
               'message'=>$message,
               'errormessage'=>$errorMessage,
            ));
    }


    /**
     * @Route("/admin/edit-salaryAccount",name="AdminEditSalaryAccount")
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
        return $this->render('EmployeeBundle:AdminManageSalaryAccount:edit_salary_account.html.twig', array(
          	'allEmployees' => $allEmployees,
          	'salaryAc' => $salaryAc,
          	'message'=>$message,
                'errormessage'=>$errormessage,
        ));
    }

     /**
     * @Route("/admin/edit-salaryAccount-submit",name="AdminEditSalaryAccountSubmit")
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
        return $this->redirectToRoute('AdminEditSalaryAccount',array(
               'message'=>$message,
               'errormessage'=>$errorMessage,
               'salaryAcId'=>$salaryAcId,
            ));
    }

    /**
     * @Route("/admin/delete-account-details-confirm/{salaryAcId}",name="AdminDeleteAccountDetails")
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
