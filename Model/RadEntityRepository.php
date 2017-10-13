<?php
namespace Mosaika\RadBundle\Model;

use Mosaika\RadBundle\Model\Query\RadQuery;

class RadEntityRepository extends RadClassable{
    /**
     * @var RadEntity
     */    
    protected $entity;
    
    /**
     * Query list
     * @var RadQuery[]
     */
    protected $query = [];
    
	/**
	 * @return \Mosaika\RadBundle\Model\RadEntity
	 */
	public function getEntity() {
		return $this->entity;
	}

	/**
	 * @param \Mosaika\RadBundle\Model\RadEntity $entity
	 * @return RadEntityRepository
	 */
	public function setEntity($entity) {
		$this->entity = $entity;
		return $this;
	}
	/**
	 * @param string $key Query name, if null return array
	 * @return \Mosaika\RadBundle\Model\Query\RadQuery[] 
	 */
	public function getQuery($key=null) {
		if($key)
			return $this->query[$key];
		return $this->query;
	}
	
	
	/**
	 * @param \Mosaika\RadBundle\Model\Query\RadQuery $query
	 * @return RadEntityRepository
	 */
	public function addQuery($query) {
		$query->setEntity($this->entity);
		$query->setSelect($this->entity->getName());
		$this->query[] = $query;
		return $this;
	}
	
	/**
	 * @param string $name
	 * @return RadQuery
	 */
	public function createQuery($name) {
		$query = new RadQuery($name, $this);
		$this->query[$name] = $query;
		return $query;
	}
	
	
}