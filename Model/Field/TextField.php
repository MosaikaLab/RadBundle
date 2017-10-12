<?php
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;

class TextField extends RadEntityField{
    
    public static function create($name){
        return new self($name,"text");
    }
    public function getPhpType(){
        return "string";
    }
    public function getAnnotations(){
        return [
            $this->getDoctrineColumnAnnotation()
        ];
    }
}

