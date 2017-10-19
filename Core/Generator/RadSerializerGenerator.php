<?php
namespace Mosaika\RadBundle\Core\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Mosaika\RadBundle\Model\RadEntity;
use Mosaika\RadBundle\Model\RadController;
use Mosaika\RadBundle\Model\Query\RadQuery;
use Mosaika\RadBundle\Model\RadControllerAction;
use Mosaika\RadBundle\Utils\GeneratorUtils;

class RadSerializerGenerator extends RadGeneratorBase {
	

	
	/**
	 * @return self
	 */
	public static function get(ContainerInterface $container){
		$builder = new RadSerializerGenerator();
		return $builder
		->setContainer($container)
		;
	}
	
	public function commit(RadEntity $entity){
		$abstractNs = $this->findNamespace("AbstractSerializer");
		$abstractName = "Abstract" . $entity->getName() . "Serializer";
		
		$ns = $this->findNamespace("Serializer");
		$name = $entity->getName() . "Serializer";
		
		$abstractClass = $this->compileTwig("serializer/abstractclass.php.twig", array(
				"entity" => $entity,
				"ns" => $abstractNs,
				"name" => $abstractName,
				"defaultForm" => $this->findNamespace("Form") . "\\" . $entity->getName() . "Type",
				"helper" => $this,
		));
		$class = $this->compileTwig("serializer/class.php.twig", array(
				"entity" => $entity,
				"ns" => $ns,
				"name" => $name,
				"parent" => "\\" . $abstractNs . "\\" . $abstractName,
				"helper" => $this,
		));
	    $abstractDir = $this->getWorkingPath("AbstractSerializer");
	    $dir = $this->getWorkingPath("Serializer");
	    
	    echo $dir . PHP_EOL;
	    echo $abstractDir. PHP_EOL;
	    
		$this->createDirs($abstractDir, $dir);
		
		$abstractPath = $abstractDir. $abstractName . ".php";
	    $path = $dir. $name . ".php";
	    
	    // Write Model class
	    echo "Writing file " . $abstractPath . PHP_EOL;
	    file_put_contents(
	        $abstractPath,
	        "<?php" . PHP_EOL .  $abstractClass
	    );
	    
	    // Write Entity class - Doesnt 
	    if(true || !file_exists($path)){
		    	echo "Writing file " . $path . PHP_EOL;
	        file_put_contents(
	            $path,
	            "<?php" . PHP_EOL . $class
	            );
	    }
        
        return $this;
	}

}