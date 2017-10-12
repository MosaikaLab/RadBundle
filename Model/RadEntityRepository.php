<?php
namespace Mosaika\RadBundle\Model;

class RadEntityRepository{
	
	
    /**
     * 
     * @var RadEntity
     */    
    protected $entity;
    
    /**
     * 
     * @param RadEntity $entity
     */
    public function __construct($entity){
	    	$this->setEntity($entity);
    }
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