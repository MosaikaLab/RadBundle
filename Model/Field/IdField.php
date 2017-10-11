<?php
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;

class IdField extends RadEntityField{
    public function getPhpType(){
        return "integer";
    }
    public function getAnnotations(){
        return [
            sprintf('@ORM\\Column(name="%s",type="integer")',$this->name),
            "@ORM\\Id",
            "@ORM\\GeneratedValue(strategy=\"AUTO\")"
        ];
    }
}

