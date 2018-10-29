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
    
    protected $indexes;
    
    
    public function __construct($name, $namespace, $bundle){
    	parent::__construct($name,$namespace,$bundle);
        $this->fields = [];
        $this->indexes = [];
        
        $this->tableName = strtolower($name);
    }
    
    /**
     * @return string Return the entity name in symfony format (Bundle:Entity)
     */
    public function getDoctrineName(){
    		return $this->bundle . ":" . ($this->namespace ? $this->namespace . "\\" : "") . $this->name;
    }
    
    /**
     * 
     * @param string $field
     * @return \Mosaika\RadBundle\Model\RadEntity
     */
    public function addIndex($field){
        $this->indexes[] = $field;
        return $this;
    }
    
    public function getIndexes(){
        return $this->indexes;
    }
    
    
    /**
     * 
     * @param RadEntityField $field
     * @param string $key
     * @return \Mosaika\RadBundle\Model\RadEntity
     */
    public function addField($field,$key=null){
    		if(!$key)
    			$key = $field->getName();
        $this->fields[$key] = $field;
        return $this;
    }
    
    /**
     * @return RadEntityField[]
     */
    public function getFields(){
    		return $this->fields;
    }
    /**
     * @param string $key 
     * @return RadEntityField
     */
    public function getField($key){
    		return $this->fields[$key];
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
     * @return self
     */
    public function setLifeCycle($lifeCycle=true){
        $this->lifeCycle = $lifeCycle;
        return $this;
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
        $this->repository = new RadEntityRepository($this->name . "Repository", $this->namespace, $this->bundle);
        $this->repository->setFullClass($this->getFullClass() . "Repository");
        return $this->repository;
    }

    
    
}

