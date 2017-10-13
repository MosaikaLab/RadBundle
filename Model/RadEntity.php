<?php
namespace Mosaika\RadBundle\Model;

class RadEntity extends RadClassable{
    
    protected $fields;
    
    protected $tableName;
    
    protected $lifeCycle;
    
    /**
     * @var RadEntityRepository
     */
    protected $repository;
    
    
    
    public function __construct($name, $namespace, $bundle){
    		parent::__construct($name,$namespace,$bundle);
        $this->fields = [];
        
        $this->tableName = strtolower($name);
    }
    
    /**
     * 
     * @param RadEntityField $field
     * @return \Mosaika\RadBundle\Model\RadEntity
     */
    public function addField($field){
        $this->fields[] = $field;
        return $this;
    }
    
    /**
     * @return RadEntityField[]
     */
    public function getFields(){
    		return $this->fields;
    }
    
    public function setTableName($tableName){
        $this->tableName = $tableName;
        return $this;
    }
    /**
     * @return string
     */
    public function getTableName(){
        return $this->tableName;
    }
    
    /**
     * @return boolean
     */
    public function getLifeCycle(){
        return $this->lifeCycle;
    }

    /**
     * @param boolean $lifeCycle
     */
    public function setLifeCycle($lifeCycle){
        $this->lifeCycle = $lifeCycle;
    }

    /**
     * @param multitype: $fields
     * @return RadEntity
     */
    public function setFields($fields){
        $this->fields = $fields;
        return $this;
    }
    
    /**
     * @return \Mosaika\RadBundle\Model\RadEntityRepository
     */
    public function getRepository(){
        return $this->repository;
    }

    /**
     * @param \Mosaika\RadBundle\Model\RadEntityRepository $repository
     * @return RadEntity
     */
    public function setRepository($repository){
        $this->repository = $repository;
        return $this;
    }
    /**
     * 
     * @return \Mosaika\RadBundle\Model\RadEntityRepository
     */
    public function createRepository(){
        $this->repository = new RadEntityRepository($this);
        return $this->repository;
    }

    
    
}

