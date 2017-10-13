<?php
namespace Mosaika\RadBundle\Model;

use Mosaika\RadBundle\Utils\GeneratorUtils;
use Nette\PhpGenerator\ClassType;
use Mosaika\RadBundle\Model\Controller\Action\ListActionConfig;

class RadEntityField{
    
    protected $name;
    
    protected $type;
    
    protected $args;
    
    protected $nullable=true;
    
    protected $defaultValue;
    
    public function getAnnotations(){
	    	return [];
    }
    
    /**
     * @param string $varName Name of the entity object
	 * @param number $strategy ListActionConfig::STRATEGY_DEFAULT|ListActionConfig::STRATEGY_DEEP
	 * @param mixed $format Format (optional)
     * @return string
     */
    public function getJsonExport($varName, $strategy=0, $format=null){
    		return sprintf('$%s->%s();',$varName, GeneratorUtils::propertyToMethod($this->name,"get"));
    }
    public function getFillFromRequest(){
    		return null;
    }
    public function getPhpType(){
        return $this->type;
    }
    /**
     * @param ClassType $modelClass
     */
    public function getMethods($modelClass){
        $name = $this->name;
        $classFullName = "\\" . $modelClass->getNamespace()->getName() . "\\" . $modelClass->getName();
        
        $setter = $modelClass->addMethod(GeneratorUtils::propertyToMethod(ucfirst($name),"set"));
        $setter->addParameter($name);
        $setter->addComment("@return " . $classFullName);
        $setter->setReturnType($classFullName);
        $setter->addBody(sprintf('$this->%s = $%s;',$name, $name));
        $setter->addBody('return $this;');
        ;
        
        $getter = $modelClass->addMethod(GeneratorUtils::propertyToMethod(ucfirst($name),"get"));
        $getter->addComment("@return " . $this->getPhpType());
        $getter->addBody(sprintf('return $this->%s;',$name));
        ;
        return [$setter,$getter];
    }
    
    public function  __construct($name, $type, $args=array()){
        $this->name = $name;
        $this->type = $type;
        $this->args = $args;
    }
    
    public function getDoctrineColumnAnnotation(){
        
        return sprintf('@Doctrine\\ORM\\Mapping\\Column(name="%s",type="%s",nullable=%s)',GeneratorUtils::propertyToDb($this->name),$this->type,$this->nullable ? "true" : "false");
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
    /**
     * @return mixed
     */
    public function getDefaultValue(){
        if(is_string($this->defaultValue)){
            return var_export($this->defaultValue, true);
        }
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @return \Mosaika\RadBundle\Model\RadEntityField
     */
    public function setDefaultValue($defaultValue){
        $this->defaultValue = $defaultValue;
        return $this;
    }


    
}

