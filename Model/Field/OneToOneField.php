<?php
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;
use Mosaika\RadBundle\Utils\GeneratorUtils;

class OneToOneField extends RadEntityField{

    public static function create($name,$ref,$mappedBy=null){
        return (new self($name,"oto"))
        ->addArg("ref",$ref)
        ->addArg("mappedBy",$mappedBy)
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
    	$mappedBy = $this->getArg("mappedBy");
        $s = "@Doctrine\ORM\Mapping\OneToOne(targetEntity=\"" . $this->getArg("ref") . "\"";
        if($mappedBy){
        		$s .= sprintf(',mappedBy="%s"',$mappedBy);
        }
        $s .= ")";

        return [$s];
    }
}

