<?php
namespace Mosaika\RadBundle\Model\Field;


class IntegerField extends StringField{
    
    public static function create($name){
        return new self($name,"integer");
    }
}

