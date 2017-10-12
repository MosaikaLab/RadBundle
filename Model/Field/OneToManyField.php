<?php
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;
use Nette\PhpGenerator\ClassType;

class OneToManyField extends RadEntityField{

    public static function create($name,$ref,$inversedBy=null){
        return (new self($name,"otm"))
        ->addArg("ref",$ref)
        ->addArg("inversedBy",$inversedBy)
        ;
    }
    public function getPhpType(){
        return str_replace("\\\\","\\","\\" . $this->getArg("ref"));
    }
    public function getAnnotations(){
        $inversedBy = $this->getArg("inversedBy");
        $s = "@Doctrine\ORM\Mapping\ManyToOne(targetEntity=\"" . $this->getArg("ref") . "\"";
        if($inversedBy){
            $s .= sprintf(',inversedBy="%s"',$inversedBy);
        }
        $s .= ")";

        return [$s];
    }
}

