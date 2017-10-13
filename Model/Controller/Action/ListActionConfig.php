<?php

namespace Mosaika\RadBundle\Model\Controller\Action;

use Mosaika\RadBundle\Model\RadEntityField;
use Mosaika\RadBundle\Utils\GeneratorUtils;

class ListActionConfig {
	const STRATEGY_DEFAULT = 0;
	const STRATEGY_DEEP = 1;
	
	/**
	 * @var array
	 */
	protected $exposedFields;
	
	/**
	 * @var bool
	 */
	protected $exposeAll;
	
	/**
	 * 
	 * @var string Query for repository
	 */
	protected $query = "findAll";
	
	public static function create(){
		return new self();
	}
	/**
	 * 
	 * @param string $field
	 * @param number $strategy STRATEGY_DEFAULT|STRATEGY_DEEP
	 * @param mixed $format Format (optional)
	 * @return ListActionConfig 
	 */
	public function addExposedField($field, $strategy=0,$format=null){
		$this->exposedFields[$field] = ["strategy" => $strategy, "format" => $format];
		return $this;
	}
	/**
	 * @param RadEntityField $entityField
	 */
	public function compileJsonExport($entityField, $varName="row"){
		$name = $entityField->getName();
		if($this->exposeAll || isset($this->exposedFields[$name])){
			$exposed = isset($this->exposedFields[$name]) ? $this->exposedFields[$name] : ['format' => null, 'strategy' => self::STRATEGY_DEFAULT];
			$fill = $entityField->getJsonExport($varName,$exposed["strategy"], $exposed["format"]);
			$action = null;
			if($fill){
				$action = sprintf('$%s = %s',$name,$fill) . PHP_EOL .
				"\t" . sprintf('$arr["%s"] = $%s;', $name, $name);
			}
			return $action;
		}
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
	
	/**
	 * @return string
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * @param string $query
	 * @return ListActionConfig
	 */
	public function setQuery($query) {
		$this->query = $query;
		return $this;
	}
	/**
	 * @return boolean
	 */
	public function isExposeAll() {
		return $this->exposeAll;
	}

	/**
	 * @param boolean $exposeAll
	 * @return ListActionConfig
	 */
	public function exposeAll($bool=true) {
		$this->exposeAll = true;
		return $this;
	}

	
}

