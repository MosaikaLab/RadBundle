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
    /**
     * 
     * @param string $name
     * @param class|string $ref The Entity class 
     * @param string $mappedBy
     */
    public static function oneToMany($name,$ref,$mappedBy=null){
        return (new RadEntityField($name, "otm"))
        ->addArg("ref",$ref)
        ->addArg("mappedBy",$mappedBy)
        ;
    }
    /**
     * Map an array of entities
     * @param string $name
     * @param class|string $ref
     * @param string $inversedBy 
     */
    public static function manyToOne($name,$ref,$inversedBy=null){
        return (new RadEntityField($name, "mto"))
        ->addArg("ref",$ref)
        ->addArg("inversedBy",$inversedBy)
        ;
    }
}

