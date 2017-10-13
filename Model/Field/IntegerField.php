<?php
namespace Mosaika\RadBundle\Model\Field;


class IntegerField extends StringField{
    
    public static function create($name){
        return new self($name,"integer");
    }
    public function getFillFromRequest(){
    	return 'intval($request->get("' . $this->name . '"));';
    }
}

