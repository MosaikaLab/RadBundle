<?php
namespace Mosaika\RadBundle\Factory;

use Mosaika\RadBundle\Model\RadEntityField;

class RadEntityFieldFactory
{
    
    public static function id($name="id"){
        return new RadEntityField($name, "id");
    }
    
    public static function string($name, $length=255){
        return new RadEntityField($name, "string");
    }
    
    public static function text($name){
        return new RadEntityField($name, "text");
    }
    public static function oneToMany($name,$ref,$mappedBy=null){
        return (new RadEntityField($name, "otm"))
        ->addArg("ref",$ref)
        ->addArg("mappedBy",$mappedBy)
        ;
    }
    public static function manyToOne($name,$ref,$mappedBy=null){
        return (new RadEntityField($name, "mto"))
        ->addArg("ref",$ref)
        ->addArg("inversedBy",$mappedBy)
        ;
    }
}

