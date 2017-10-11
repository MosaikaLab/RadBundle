<?php
namespace Mosaika\RadBundle\Model;

class RadEntityField{
    
    protected $name;
    
    protected $type;
    
    protected $args;
    
    protected $nullable=true;
    
    public function  __construct($name, $type, $args=array()){
        $this->name = $name;
        $this->type = $type;
        $this->args = $args;
    }
    
    
    public function setNullable($nullable){
        $this->nullable = $nullable;
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * @return mixed
     */
    public function getArgs(){
        return $this->args;
    }
    
    /**
     * @return mixed
     */
    public function getArg($arg){
        return isset($this->args[$arg]) ? $this->args[$arg] : null;
    }
    /**
     * 
     * @param string $arg
     * @param mixed $val
     * @return \Mosaika\RadBundle\Model\RadEntityField
     */
    public function addArg($arg,$val){
        $this->args[$arg] = $val;
        return $this;
    }
    
    /**
     * @param mixed $args
     */
    public function setArgs($args){
        $this->args = $args;
    }

    /**
     * @return boolean
     */
    public function getNullable(){
        return $this->nullable;
    }

    
}

