<?php
namespace Mosaika\RadBundle\Model\Query;

use Mosaika\RadBundle\Model\RadEntityRepository;

class RadQueryFilter{
	const SOURCE_INPUT = 1;
	const SOURCE_REQUEST = 2;
	const SOURCE_CONTEXT = 4;
	
    /**
     * Field you want to filter
     * @var string
     */
    protected $scope = self::SOURCE_REQUEST;
    
    /**
     * Operator: %like% %like like% like = > < != between
     * @var string 
     */
    protected $operator = "=";
    
    /**
     * Filter value, for non exposed filters
     * @var mixed 
     */
    protected $value;
    
    /**
     * If true, filter value will be provided by user or by context
     * If false, a fixed (even if calculated) value is explicitly provided
     * @var boolean
     */
    protected $exposed = false;
    
    /**
     * Example RadQueryFilter::SOURCE_INPUT
     * @var integer 
     */
    protected $source;
    
    /**
     * 
     * @var RadQuery
     */
    protected $query;
    
    
	/**
	 * @return string
	 */
	public function getScope() {
		return $this->scope;
	}

	/**
	 * @param string $scope
	 * @return RadQueryFilter
	 */
	public function setScope($scope) {
		$this->scope = $scope;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOperator() {
		return $this->operator;
	}

	/**
	 * @param string $operator
	 * @return RadQueryFilter
	 */
	public function setOperator($operator) {
		$this->operator = $operator;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param mixed $value
	 * @return RadQueryFilter
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isExposed() {
		return $this->exposed;
	}

	/**
	 * @param boolean $exposed
	 * @return RadQueryFilter
	 */
	public function setExposed($exposed=TRUE) {
		$this->exposed = $exposed;
		if($exposed){
			$this->source = self::SOURCE_INPUT;
		}
		return $this;
	}
	/**
	 * @return number
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param number $source
	 * @return RadQueryFilter
	 */
	public function setSource($source) {
		$this->source = $source;
		return $this;
	}
	/**
	 * @return \Mosaika\RadBundle\Model\Query\RadQuery
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * @param \Mosaika\RadBundle\Model\Query\RadQuery $query
	 * @return RadQueryFilter
	 */
	public function setQuery($query) {
		$this->query = $query;
		return $this;
	}


   	
}

