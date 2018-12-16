<?php
namespace Mosaika\RadBundle\Model;

class RadEntity extends RadClassable{
    
    protected $fields;
    
    protected $tableName;

    protected $tableOtions;
    
    protected $tablePrefix;
    
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
    
    /**
     * @param array $tableName 
     * @return this
     */
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
     * @return string
     */
    public function getTablePrefix(){
        return $this->tablePrefix;
    }

    /**
     * @return self
     */
    public function setTablePrefix($tp){
        $this->tablePrefix = $tp;
        return $this;
    }
    
    /**
     * @param array $tableOtions 
     * @return this
     */
    public function setTableOptions($tableOtions){
        $tableOtions = !is_array($tableOtions) ? [] : $tableOtions;
        $this->tableOtions = $tableOtions;
        return $this;
    }
    
    /**
     * @param boolean $asORMString
     * @return string
     */
    public function getTableOptions( $asORMString=true ){
        $tableOtions = !is_array( $this->tableOtions ) ? [] : $this->tableOtions;
        
        if( $asORMString ){
            //return as ORM Format String 'options'
            $options = [];
            foreach( $tableOtions as $tableOtion => $tableOtionValue ){
                $options[] = '"'.$tableOtion .'":"'.$tableOtionValue.'"';
            }
            return count($options) > 0 ? (' options={'.implode(", ", $options).'}' ) : '';
        }
        else{
            //return simply as array
            return $tableOtions;
        }
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

