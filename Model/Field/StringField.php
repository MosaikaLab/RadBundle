<?php
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;

class StringField extends RadEntityField{
    
    public static function create($name){
        return new self($name,"string");
    }
    public function getAnnotations(){
        return [
            $this->getDoctrineColumnAnnotation()
        ];
    }
    public function getFillFromRequest(){
    		return '$request->get("' . $this->name . '");'; 
    }
}

