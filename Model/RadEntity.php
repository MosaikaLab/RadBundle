<?php
namespace Mosaika\RadBundle\Model;

class RadEntity{
    protected $bundle;
    
    protected $fields;
    
    protected $tableName;
    
    protected $name;
    
    
    
    public static function create($name, $namespace, $bundle=null){
        return new RadEntity($name, $namespace, $bundle);
    }
    
    public function __construct($name, $namespace, $bundle=null){
        $this->name = $name;
        $this->namespace = $namespace;
        $this->bundle = $bundle;
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
    
    public function setTableName($tableName){
        $this->tableName = $tableName;
        return $this;
    }
    /**
     * @return string
     */
    public function getBundle(){
        return $this->bundle;
    }

    /**
     * @return string
     */
    public function getTableName(){
        return $this->tableName;
    }
    
    /**
     * @return mixed
     */
    public function getName(){
        return $this->name;
    }
    /**
     * @return RadEntityField[]
     */
    public function getFields(){
        return $this->fields;
    }

    /**
     * @param string $bundle
     * @return RadEntity
     */
    public function setBundle($bundle){
        $this->bundle = $bundle;
        return $this;
    }

    /**
     * @param mixed $name
     * @return RadEntity
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }

    
}

