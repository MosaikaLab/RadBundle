<?php

namespace Mosaika\RadBundle\Model\Controller\Action;

use Mosaika\RadBundle\Model\RadEntityField;
use Mosaika\RadBundle\Utils\GeneratorUtils;

class SaveActionConfig {
	/**
	 * 
	 * @var array
	 */
	protected $exposedFields;
	
	
	public static function create(){
		return new self();
	}
	/**
	 * 
	 * @param RadEntityField $entityField
	 */
	public function compileFillFromRequest($entityField){
		$name = $entityField->getName();
		$fill = $entityField->getFillFromRequest();
		$action = null;
		if($fill){
			$action = sprintf('$%s = %s',$name,$fill) . PHP_EOL .
			sprintf('$item->%s($%s);', GeneratorUtils::propertyToMethod($name,"set"), $name);
		}
		return $action;
	}
	/**
	 * 
	 * @param array $exposedFields Array of fields that can be filled from request. If null, all fields (except the ID) will be filled
	 */
	public function setExposedFields($exposedFields){
		if(!$exposedFields){
			$exposedFields = array_map(function($field){
				return $field->getName();
			}, $this->entity->getFields());
		}
		$this->exposedFields = $exposedFields;
		return $this;
	}
}

