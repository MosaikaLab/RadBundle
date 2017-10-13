<?php
namespace Mosaika\RadBundle\Model;

class RadEntityRepository extends RadClassable{
    /**
     * @var RadEntity
     */    
    protected $entity;
    
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
	
}