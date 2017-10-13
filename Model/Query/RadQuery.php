<?php

namespace Mosaika\RadBundle\Model\Query;


use Mosaika\RadBundle\Model\RadEntity;
use Mosaika\RadBundle\Model\RadEntityRepository;

class RadQuery {
	/**
	 * 
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var boolean 
	 */
	protected $paginator = false;
	
	/**
	 * @var integer
	 */
	protected $pageSize = 25;
	
	/**
	 * @var integer
	 */
	protected $maxResult;
	
	/**
	 *
	 * @var RadQueryFilter[]
	 */
	protected $filters;
	/**
	 *
	 * @var string[]
	 */
	protected $orderBy;
	
	/**
	 * @var string[]
	 */
	protected $select;
	
	/**
	 *
	 * @var RadEntity
	 */
	protected $entity;
	
	/**
	 *
	 * @var RadEntityRepository
	 */
	protected $repository;
	
	/**
	 * 
	 * @param string $name
	 * @param RadEntityRepository $repository
	 */
	public function __construct($name,$repository){
		$this->name = $name;
		
		$this->setEntity($repository->getEntity());
		$this->setRepository($repository);
		$this->setSelect($this->entity->getName());
		$this->filters = [];
	}
    /**
     * @return boolean
     */
    public function isPaginator()
    {
        return $this->paginator;
    }

    /**
     * @return number
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param boolean $paginator
     * @return RadQuery
     */
    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
        return $this;
    }

    /**
     * @param number $pageSize
     * @return RadQuery
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }
    /**
     * @return multitype:\Mosaika\RadBundle\Model\Query\RadQueryFilter
     */
    public function getFilters() {
    		return $this->filters;
    }
    
    /**
     * @param \Mosaika\RadBundle\Model\Query\RadQueryFilter  $filter
     * @return RadQuery
     */
    public function addOrderBy($orderBy,$orderDir="asc") {
    		$this->orderBy[] = $orderBy . " " . $orderDir;
    		return $this;
    }
    /**
     * @return string[]
     */
    public function getOrderBy() {
    		return $this->orderBy;
    }
    
    /**
     * @param \Mosaika\RadBundle\Model\Query\RadQueryFilter  $filter
     * @return RadQuery
     */
    public function addFilter($filter) {
	    	$this->filters[] = $filter;
	    	return $this;
    }
    
    /**
     * @return RadQueryFilter
     */
    public function createFilter($key) {
	    	$filter = new RadQueryFilter();
	    	$filter->setQuery($this);
	    	$filter->setName($key);
	    	$this->filters[$key] = $filter;
    		return $filter;
    }

	/**
	 * @return multitype:string 
	 */
	public function getSelect() {
		return $this->select;
	}

	/**
	 * @param multitype:string  $select
	 * @return RadQuery
	 */
	public function setSelect($select) {
		$this->select = $select;
		return $this;
	}

	/**
	 * @return \Mosaika\RadBundle\Model\RadEntity
	 */
	public function getEntity() {
		return $this->entity;
	}

	/**
	 * @param \Mosaika\RadBundle\Model\RadEntity $entity
	 * @return RadQuery
	 */
	public function setEntity($entity) {
		$this->entity = $entity;
		return $this;
	}
	/**
	 * @return number
	 */
	public function getMaxResult() {
		return $this->maxResult;
	}

	/**
	 * @param number $maxResult
	 * @return RadQuery
	 */
	public function setMaxResult($maxResult) {
		$this->maxResult = $maxResult;
		return $this;
	}

	/**
	 * @return \Mosaika\RadBundle\Model\RadEntityRepository
	 */
	public function getRepository() {
		return $this->repository;
	}

	/**
	 * @param \Mosaika\RadBundle\Model\RadEntityRepository $repository
	 * @return RadQuery
	 */
	public function setRepository($repository) {
		$this->repository = $repository;
		return $this;
	}
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return RadQuery
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	
	
}