<?php
namespace Mosaika\RadBundle\Model\Field;


class BooleanField extends StringField{
    
    public static function create($name){
        return new self($name,"boolean");
    }
    public function getFillFromRequest(){
	    	return '!(!($request->get("' . $this->name . '")));';
    }
}

