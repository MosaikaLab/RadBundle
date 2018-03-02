<?php 
namespace Mosaika\RadBundle\Model\Field;

use Mosaika\RadBundle\Model\RadEntityField;
use Nette\PhpGenerator\ClassType;

class ManyToManyField extends RadEntityField{
    const AC = '\Doctrine\Common\Collections\ArrayCollection';
    public static function create($name,$ref,$inversedBy=null,$joinName=null,$mappedBy=null){
        return (new self($name,"mto"))
        ->addArg("ref",$ref)
        ->addArg("inversedBy",$inversedBy)
        ->addArg("joinName",$joinName)
        ->addArg("mappedBy",$mappedBy)
        ;
    }
    public function getPhpType(){
        return str_replace("\\\\","\\","\\" . $this->getArg("ref")) . '[]|' . self::AC;
    }

    /**
     * @param ClassType $modelClass
     * {@inheritDoc}
     * @see \Mosaika\RadBundle\Model\RadEntityField::getMethods()
     */
    public function getMethods($modelClass){
        $classFullName = "\\" . $modelClass->getNamespace()->getName() . "\\" . $modelClass->getName();
        $res = parent::getMethods($modelClass);
        $setterBody = explode("\n",$res[0]->getBody());
        $res[0]->setBody("");
        $res[0]->addBody(sprintf('if(!$%s instanceof ' . self::AC .'){ $%s = new '. self::AC .'($%s); }',$this->name,$this->name,$this->name));
        foreach($setterBody as $s){
            $res[0]->addBody($s);
        }
        $res[] = $modelClass->addMethod("add" . substr($res[0]->getName(),3))
        ->setReturnType($classFullName)
        ->addComment("@return " . $classFullName)
        ->addBody(sprintf('$this->%s->add($item);',$this->name))
        ->addBody('return $this;')
        ->addParameter("item")
        ;
        return $res;
    }

    public function getDefaultValue(){
        return "new " . self::AC . "()";
    }

    public function getAnnotations(){
        $mappedBy = $this->getArg("mappedBy");
        $inversedBy= $this->getArg("inversedBy");
        $joinName= $this->getArg("joinName");
        $s = "@Doctrine\ORM\Mapping\ManyToMany(cascade={\"persist\",\"detach\"}, targetEntity=\"" . $this->getArg("ref") . "\"";
        if($mappedBy){
            $s .= sprintf(',mappedBy="%s"',$mappedBy);
            $s .= ")";
            return [$s];
        }elseif($inversedBy){
            $s .= sprintf(',inversedBy="%s"',$inversedBy);
            $s .= ")";
            $j = "Doctrine\ORM\Mapping\JoinTable(name=\"".$joinName."\")";
            return [$s,$j];
        }        
    }
}