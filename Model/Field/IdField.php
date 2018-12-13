<?php
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;

class IdField extends RadEntityField{
    
    public static function create($name="id",$args=array("strategy"=>"AUTO")){
        return new self($name,"id", $args);
    }
    public function getPhpType(){
        return "integer";
    }
    public function getAnnotations(){
        $annotations = [
            sprintf('@Doctrine\ORM\Mapping\\Column(name="%s",type="integer")',$this->name),
            "@Doctrine\ORM\Mapping\\Id",
        ];

        $allowedStrategies = [ 
            'AUTO',
            'SEQUENCE',
            'IDENTITY',
            'TABLE',
            'NONE',
            'UUID',
        ];

        if( $this->getArg('strategy') != null && in_array( $this->getArg('strategy'), $allowedStrategies ) ){
            $annotations[] = "@Doctrine\ORM\Mapping\\GeneratedValue(strategy=\"".$this->getArg('strategy')."\")";
        }

        return $annotations;
    }
}

