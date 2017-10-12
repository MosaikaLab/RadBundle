<?php
namespace Mosaika\RadBundle\Model\Field;


class DecimalField extends FloatField{
    
    public static function create($name){
        return new self($name,"decimal");
    }
}

