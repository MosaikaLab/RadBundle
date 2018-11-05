<?php
namespace Mosaika\RadBundle\Model\Field;


class EnumField extends StringField{
    protected $values = array();
    
    public static function create($name){
        return new self($name,"string");
    }
    
    public function getColumnDefinition(){
        return "ENUM(" . implode(",",array_map(function($value){
            return "'" . $value . "'";
        },$this->values)) . ")";
    }
    
    /**
     * @param string $v
     * @return self
     */
    public function addValue($v1,$v2,$vn){
        $args = func_get_args();
        foreach ($args as $v){
            $this->values[] = $v;
        }
        return $this;
    }
}

