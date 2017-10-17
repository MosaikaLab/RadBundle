<?php
namespace Mosaika\RadBundle\Model\Field;


class DateField extends StringField{
    
	protected $now = false;
	
    public static function create($name){
        return new self($name,"date");
    }
    public function getPhpType(){
        return "\DateTime";
    }
    public function getDefaultValue(){
    		return $this->now ? "new \DateTime()" : parent::getDefaultValue();  
    }
    /**
     *
     * @return \Mosaika\RadBundle\Model\Field\DateField
     */
    public function setNowDefaultValue($b=true){
	    	$this->now = $b;
	    	return $this;
    }
    public function getAnnotations(){
	    	$a = parent::getAnnotations();
	    	$a[] = '@JMS\Serializer\Annotation\Type("DateTime<\'U\'>")';
	    	return $a;
    }
    
    public function getFormTypeAttributes(){
    		return array("widget" => "single_text");
    }
    
}

