<?php
namespace Mosaika\RadBundle\Core\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Mosaika\RadBundle\Model\RadEntity;

class RadEntityGenerator extends RadGeneratorBase {
	

	
	protected $hasLifecycle = false;
	
	
	protected $parent = "";

	/**
	 * 
	 * @var bool
	 */
	protected $crud;
	
	
	/**
	 *
	 * @return \Mosaika\RadBundle\Core\Generator\RadEntityGenerator
	 */
	public static function get(ContainerInterface $container){
		$builder = new RadEntityGenerator();
		return $builder
		->setBaseNamespace("Entity")
		->setContainer($container)
		;
	}
	
	public function commit(RadEntity $entity){
	    $modelNs = new PhpNamespace($this->findNamespace("Model"));
	    $modelNs
//	    ->addUse("Doctrine\ORM\Mapping","ORM")
//	    ->addUse("Symfony\Component\Validator\Constraints","Assert")
	    ;
	    
	    $modelClass = (new ClassType($entity->getName() . "Model", $modelNs))
	    ->setAbstract(true)
	    ->addComment("Autogenerated file. Do not edit this file, edit the Entity insted")
	    ;
	    $constructor = $modelClass->addMethod("__construct");
	    foreach($entity->getFields() as $field){
	        $name = $field->getName();
	        $value = $field->getDefaultValue();
	        $phpType = $field->getPhpType();
	        
	        $property = $modelClass->addProperty($name);
	        $annotations = $field->getAnnotations();
	        $annotations[] = '@var ' . $phpType;
	        
	        foreach($annotations as $a){
	            $property->addComment($a);
	        }
	        if($value){
	            $constructor->addBody(sprintf('$this->%s = %s;',$name,$value));
	        }
	        
	        $property->setVisibility("protected");
	        
	        $methods = $field->getMethods($modelClass);
	        foreach($methods as $m){
	            $modelClass->methods[] = $m;
	        }
	        
	    }
	    
	    $entityNs = new PhpNamespace($this->findNamespace("Entity"));
	    //$entityNs->addUse("Symfony\Component\Validator\Constraints","Assert");
	    $entityClass = (new ClassType($entity->getName(), $entityNs));
	    $entityClass->setExtends($modelNs->getName() . "\\" . $modelClass->getName());
	    
	    $entityClass->addComment(
	    		sprintf('@Doctrine\ORM\Mapping\Table(name="%s")',$entity->getTableName())
	    	);
	    
	    $modelDir = $this->getWorkingPath("Model");
	    $formDir = $this->getWorkingPath("Form");
	    $entityDir = $this->getWorkingPath();
	    
	    $this->createDirs($modelDir,$entityDir,$formDir,$formDir);
	    
	    $modelPath = $modelDir. $modelClass->getName() . ".php";
	    $formPath = $formDir. $modelClass->getName() . "Type.php";
	    $entityPath = $entityDir. $entityClass->getName() . ".php";
	    
	    // Create Repository
	    $repositoryClass = null;
	    if($entity->getRepository()){
		    	$repositoryClass = sprintf('repositoryClass="%s"',$entity->getRepository()->getFullClass());
	    }
	    $entityClass->addComment(sprintf('@Doctrine\ORM\Mapping\Entity(%s)',$repositoryClass));
	    
	    // Write Model class
	    echo "Writing file: " . $modelPath . PHP_EOL;
	    file_put_contents(
	        $modelPath,
	        "<?php" . PHP_EOL . $modelNs . $modelClass
	    );
	    
	    // Write Entity class - Doesnt 
	    if(true || !file_exists($entityPath)){
		    	echo "Writing file " . $entityPath . PHP_EOL;
	        file_put_contents(
	            $entityPath,
	            "<?php" . PHP_EOL . $entityNs . $entityClass
	            );
	    }
	    if(true || !file_exists($formPath)){
	    	$this->runCommand(["command" => "generate:doctrine:form","entity" => $entity->getDoctrineName()]);
	    }
        return $this;
	}

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }
	public function getCrud() {
		return $this->crud;
	}
	public function setCrud($crud) {
		$this->crud = $crud;
		return $this;
	}
}