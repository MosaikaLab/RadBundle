<?php
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;

class IdField extends RadEntityField{
    
    public static function create($name="id"){
        return new self($name,"id");
    }
    public function getPhpType(){
        return "integer";
    }
    public function getAnnotations(){
        return [
            sprintf('@Doctrine\ORM\Mapping\\Column(name="%s",type="integer")',$this->name),
            "@Doctrine\ORM\Mapping\\Id",
            "@Doctrine\ORM\Mapping\\GeneratedValue(strategy=\"AUTO\")"
        ];
    }
}

