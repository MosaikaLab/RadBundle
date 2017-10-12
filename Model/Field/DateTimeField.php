<?php
namespace Mosaika\RadBundle\Model\Field;


class DateTimeField extends DateField{
    
    public static function create($name){
        return new self($name,"datetime");
    }
    
}

