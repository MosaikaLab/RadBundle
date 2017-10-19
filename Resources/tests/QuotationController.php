<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\IncomeItem;
use AppBundle\Form\IncomeItemType;
use AppBundle\Entity\OutcomeItem;
use AppBundle\Form\OutcomeItemType;
use AppBundle\Serializer\IncomeItemSerializer;
use AppBundle\Serializer\OutcomeItemSerializer;
use AppBundle\Serializer\QuotationPaymentSerializer;

class QuotationController extends \AppBundle\AbstractController\AbstractQuotationController
{
	
	public function _onGetRest(\AppBundle\Entity\Quotation $item): \AppBundle\Entity\Quotation
	{
		$em = $this->getDoctrine()->getManager();
		$items = array_merge(
				$em->getRepository("AppBundle:IncomeItem")->findBy(array("quotation" => $item), array("ordering" => "asc")),
				$em->getRepository("AppBundle:OutcomeItem")->findBy(array("quotation" => $item), array("ordering" => "asc"))
		);
		$payments = $em->getRepository("AppBundle:QuotationPayment")->findBy(array("quotation" => $item), array("ordering" => "asc"));
		$item->setItems($items);
		$item->setPayments($payments);
		return $item;
	}
	
	public function _onFillQuotation(\AppBundle\Entity\Quotation $item, array $input, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$serializers = ["income" => new IncomeItemSerializer($this->container), "outcome" => new OutcomeItemSerializer($this->container), "payment" => new QuotationPaymentSerializer($this->container)];
		$item->setUpdatedAt(new \DateTime());
		if(isset($input["items"])){
			foreach($input["items"] as $index => $quotationItemArray){
				$serializer = $serializers[$quotationItemArray["type"]];
				$quotationItem = $serializer->unserialize($quotationItemArray);
				if($quotationItem){
					if(isset($quotationItemArray[".delete"]) && $quotationItemArray[".delete"]){
						$em->remove($quotationItem);
					}else{
						$quotationItem->setQuotation($item);
						$quotationItem->setOrdering($index);
						$em->persist($quotationItem);
					}
				}
			}
		}
		if(isset($input["payments"])){
			foreach($input["payments"] as $index => $quotationPaymentArray){
				$serializer = $serializers["payment"];
				$quotationPayment = $serializer->unserialize($quotationPaymentArray);
				if($quotationItem){
					if(isset($quotationPaymentArray[".delete"]) && $quotationPaymentArray[".delete"]){
						$em->remove($quotationPayment);
					}else{
						$quotationPayment->setQuotation($item);
						$quotationPayment->setOrdering($index);
						$em->persist($quotationPayment);
					}
				}
			}
		}
	}
}
