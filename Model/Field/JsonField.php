<?php
namespace Mosaika\RadBundle\Model\Field;


use Nette\PhpGenerator\ClassType;

class JsonField extends TextField{
    
    public static function create($name){
        return new self($name,"text");
    }
    public function getPhpType(){
        return "object";
    }
    
    /**
     * @param ClassType $modelClass
     * {@inheritDoc}
     * @see \Mosaika\RadBundle\Model\RadEntityField::getMethods()
     */
    public function getMethods($modelClass){
        /**
         * @var Method[] $res
         */
        $res = parent::getMethods($modelClass);
        $setterBody = explode("\n",$res[0]->getBody());
        $res[0]->setBody("");
        $res[0]->addBody(sprintf('if(is_object($%s) || is_array($%s)){ $%s = json_encode($%s); }',$this->name,$this->name,$this->name,$this->name));
        foreach($setterBody as $s){
            $res[0]->addBody($s);
        }
        
        
        $keyMethod = $modelClass->addMethod($res[1]->getName())
        ->addComment("@return mixed")
        ->addBody(sprintf('if(is_string($this->%s)){ $%s = json_decode($this->%s,true); }',$this->name,$this->name,$this->name))
        ->addBody(sprintf('else{ $%s = $this->%s; }',$this->name,$this->name))
        ->addBody(sprintf('if(!$key) return $%s;',$this->name)) 
        ->addBody('$parts = explode(".",$key);')
        ->addBody(sprintf('$var = $%s();',$res[1]->getName()))
        ->addBody('foreach($parts as $p){')
        ->addBody('	if(!isset($var[$p]))')
        ->addBody('		return $defaultValue;')
        ->addBody(' $var = $var[$p];')
        ->addBody('}')
        ->addBody('return $var;')
        ;
        $keyMethod->addParameter("key",null);
        $keyMethod->addParameter("defaultValue",null);
        /*
         * 
         */
        ;
        $res[1] = $keyMethod;
        return $res;
    }
}

