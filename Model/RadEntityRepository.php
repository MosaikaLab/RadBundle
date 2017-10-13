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
	 * @return \Mosaika\RadBundle\Model\Query\RadQuery[] 
	 */
	public function getQuery() {
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
		$this->query[] = $query;
		return $query;
	}
	
	
}