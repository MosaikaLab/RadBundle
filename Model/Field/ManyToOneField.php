<?php
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;
use Mosaika\RadBundle\Utils\GeneratorUtils;

class ManyToOneField extends RadEntityField{

    public static function create($name,$ref,$inversedBy=null){
        return (new self($name,"otm"))
        ->addArg("ref",$ref)
        ->addArg("inversedBy",$inversedBy)
        ;
    }
    public function getPhpType(){
        return str_replace("\\\\","\\","\\" . $this->getArg("ref"));
    }
    
    /**
     * @param string $varName Name of the entity object
     * @param number $strategy ListActionConfig::STRATEGY_DEFAULT|ListActionConfig::STRATEGY_DEEP
     * @param mixed $format Format (optional)
     * @return string
     */
    public function getJsonExport($varName, $strategy=0, $format=null){
	    	$res = sprintf('$%s->%s()',$varName, GeneratorUtils::propertyToMethod($this->name,"get"));
	    	if($strategy==0){
	    		return $res . " ? " . $res . "->getId() : null;";
	    	}else{
	    		return "NOOOO;";
	    	}
	    		
	    
    }
    public function getAnnotations(){
        $inversedBy = $this->getArg("inversedBy");
        $s = "@Doctrine\ORM\Mapping\ManyToOne(cascade={\"persist\"}, targetEntity=\"" . $this->getArg("ref") . "\"";
        if($inversedBy){
            $s .= sprintf(',inversedBy="%s"',$inversedBy);
        }
        $s .= ")";

        return array_merge(parent::getAnnotations(), [
            $s
        ]);
    }
}

