<?php
namespace Mosaika\RadBundle\Model\Field;


class FloatField extends StringField{
    
    public static function create($name){
        return new self($name,"float");
    }
    public function getPhpType(){
        return "float";
    }
    public function getFillFromRequest(){
    	return 'floatval($request->get("' . $this->name . '"));';
    }
}

