<?php
namespace Mosaika\RadBundle\Model\Field;


class TextField extends StringField{
    
    public static function create($name){
        return new self($name,"text");
    }
    public function getPhpType(){
        return "string";
    }
}

