<?php
namespace Mosaika\RadBundle\Model\Field;


class DateField extends StringField{
    
    public static function create($name){
        return new self($name,"date");
    }
    public function getPhpType(){
        return "\DateTime";
    }
    
}

