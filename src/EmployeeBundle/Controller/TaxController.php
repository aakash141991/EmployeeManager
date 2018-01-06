<?php

namespace EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use EmployeeBundle\Entity\TaxSlab;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaxController extends Controller
{

	/**
     * @Route("/admin/update-tax-slab",name="updateTaxSlab")
     */
    public function updateTaxSlabAction(Request $request)
    {
            $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errorMessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
                  $taxes=$this->getDoctrine()->getRepository(TaxSlab::class)->findAll();

        return $this->render('EmployeeBundle:Tax:update_tax.html.twig', array(
             'taxes'=>$taxes,
             'errormessage'=>$errormessage,
             'message'=>$message,
         
         
        ));
    }
    /**
     * @Route("/admin/update-tax-slab/{taxId}",name="updateTaxSlabSingle")
     */
    public function updateTaxSlabSingleAction($taxId,Request $request)
    {

          $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errorMessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        $taxSlab=$this->getDoctrine()->getRepository(TaxSlab::class)->find($taxId);

        return $this->render('EmployeeBundle:Tax:update_tax_single.html.twig', array(
             'taxSlab'=>$taxSlab,
             'errormessage'=>$errormessage,
             'message'=>$message,
         
        ));
    }

    /**
     * @Route("/admin/update-tax-slab-submit",name="updateTaxSlabSubmit")
     */
    public function updateTaxSlabSubmitAction(Request $request)
    {
          $taxId = $request->request->get('taxId');
        $incomeFrom = $request->request->get('incomeFrom');
        $incomeUpto = $request->request->get('incomeUpto');
        $taxRate = $request->request->get('taxRate');
        $cessRate = $request->request->get('cessRate');
        $valid = false;
         $errorMessage = "";
         $message="";

        if(isset($incomeFrom) && isset($taxId) && isset($incomeUpto) && isset($taxRate) && isset($cessRate)){
            $valid = true;
        }else{
            $errorMessage = "All Fields should be filled before submitting.";
        }

        if($valid){
            if($incomeUpto != 0){
                 if( $incomeFrom >= $incomeUpto ){
                $errorMessage = "Income  range not proper";
                $valid = false;
            }
            }
           
        }

        if($valid){
             $taxes=$this->getDoctrine()->getRepository(TaxSlab::class)->findAll();

            foreach($taxes as $tax){
                if($tax->getId() == $taxId){
                    continue;
                }else{
                     $startIncome = $tax->getIncomeFrom();
                $endIncome = $tax->getIncomeUpto();
                if(($incomeUpto == 0)&&($endIncome == 0)){
                    $errorMessage = "Income ranges selected overlaps with other Tax Slabs defined";
                        $valid = false;
                        break;
                }
                if(($incomeFrom < $startIncome)&&($incomeUpto <= $startIncome)){
                    continue;
                }
                elseif($incomeFrom >= $endIncome){
                    continue;
                }else{
                    $errorMessage = "Income ranges selected overlaps with other Tax Slabs defined";
                        $valid = false;
                        break;
                }

                }
               
            }
        }
        

        if($valid){
            $taxSlab=$this->getDoctrine()->getRepository(TaxSlab::class)->find($taxId);
            $taxSlab->setIncomeFrom($incomeFrom);
             $taxSlab->setIncomeUpto($incomeUpto);
              $taxSlab->setTaxRate($taxRate);
               $taxSlab->setCess($cessRate);

              $en = $this->getDoctrine()->getManager();
                $en->merge($taxSlab);
                $en->flush();
                $message="Tax slab Updated successfully";


        }

       
        if(($errorMessage == "") && (!isset($taxId))){
                return $this->redirectToRoute('updateTaxSlab',array(
                        'errorMessage'=>$errorMessage,
             'message'=>$message,
                    ));
        }else{

             $taxSlab=$this->getDoctrine()->getRepository(TaxSlab::class)->find($taxId);
              return $this->render('EmployeeBundle:Tax:update_tax_single.html.twig', array(
             'taxSlab'=>$taxSlab,
             'errormessage'=>$errorMessage,
             'message'=>$message,
                 
                ));
            }
        
        
       
    }

     /**
     * @Route("/admin/add-tax-slab",name="addTaxSlab")
     */
    public function addTaxSlabAction(Request $request)
    {

          $message=$request->query->get('message');
          if(!isset($message)){
              $message="";
            }
            $errormessage=$request->query->get('errorMessage');
            if(!isset($errormessage)){
              $errormessage="";
            }
        
        return $this->render('EmployeeBundle:Tax:add_tax_slab.html.twig', array(
             'errormessage'=>$errormessage,
             'message'=>$message,
         
        ));
    }

   /**
     * @Route("/admin/add-tax-slab-submit",name="addTaxSlabSubmit")
     */
    public function addTaxSlabSubmitAction(Request $request)
    {
         
        $incomeFrom = $request->request->get('incomeFrom');
        $incomeUpto = $request->request->get('incomeUpto');
        $taxRate = $request->request->get('taxRate');
        $cessRate = $request->request->get('cessRate');
        $valid = false;
         $errorMessage = "";
         $message="";
         /*$startIncome = double($incomeFrom);
         $endIncome = double( $incomeUpto);*/

        if(isset($incomeFrom) && isset($incomeUpto) && isset($taxRate) && isset($cessRate)){
            $valid = true;
        }else{
            $errorMessage = "All Fields should be filled before submitting.";
        }

        if($valid){
            if($incomeUpto != 0){
                 if( $incomeFrom >= $incomeUpto ){
                $errorMessage = "Income  range not proper";
                $valid = false;
            }
            }
           
        }

        if($valid){
             $taxes=$this->getDoctrine()->getRepository(TaxSlab::class)->findAll();

            foreach($taxes as $tax){
                $startIncome = $tax->getIncomeFrom();
                $endIncome = $tax->getIncomeUpto();
                if(($incomeUpto == 0)&&($endIncome == 0)){
                    $errorMessage = "Income ranges selected overlaps with other Tax Slabs defined";
                        $valid = false;
                        break;
                }
                if(($incomeFrom < $startIncome)&&($incomeUpto <= $startIncome)){
                    continue;
                }
                elseif($incomeFrom >= $endIncome){
                    continue;
                }else{
                    $errorMessage = "Income ranges selected overlaps with other Tax Slabs defined";
                        $valid = false;
                        break;
                }
            }
        }
        

        if($valid){

            $taxSlab=new TaxSlab;
            $taxSlab->setIncomeFrom($incomeFrom);
             $taxSlab->setIncomeUpto($incomeUpto);
              $taxSlab->setTaxRate($taxRate);
               $taxSlab->setCess($cessRate);

              $en = $this->getDoctrine()->getManager();
                $en->persist($taxSlab);
                $en->flush();
                $message="Tax slab Submitted successfully";


        }

       
        if(($errorMessage == "")){
                return $this->redirectToRoute('updateTaxSlab',array(
                        'errorMessage'=>$errorMessage,
             'message'=>$message,
                    ));
        }else{

               return $this->render('EmployeeBundle:Tax:add_tax_slab.html.twig', array(
             'errormessage'=>$errorMessage,
             'message'=>$message,
         
        ));
            }
        
        
       
    }


      /**
     * @Route("/admin/remove-tax-submit/{taxId}",name="removeTaxSlab")
     */
    public function removeTaxSlabAction($taxId)
    {
            $message="";
            
            
            if(isset($taxId)){
            $taxSlab=$this->getDoctrine()->getRepository(TaxSlab::class)->find($taxId);
            $en = $this->getDoctrine()->getManager();
                $en->remove($taxSlab);
                $en->flush();
                    $message="Tax Slab removed successfully";
            }else{
                $message ="Something Went wrong";
            }
        
       return new JsonResponse(array(
          'data'=>$message,
                
          ));
    }
    


   

    



}
