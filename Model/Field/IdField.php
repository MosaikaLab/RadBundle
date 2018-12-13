<?php
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;

class IdField extends RadEntityField{
    
    public static function create($name="id",$strategy="AUTO"){
        $this->strategy = strtoupper($strategy);
        return new self($name,"id");
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

        if( in_array( $this->strategy, $allowedStrategies ) ){
            $annotations[] = "@Doctrine\ORM\Mapping\\GeneratedValue(strategy=\"".$this->strategy."\")";
        }
        
        return $annotations;
    }
}

