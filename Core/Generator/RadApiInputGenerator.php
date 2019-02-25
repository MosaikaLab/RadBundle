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
use Mosaika\RadBundle\Model\RadApiInput;

class RadApiInputGenerator extends RadGeneratorBase {
	

	
	/**
	 * @return self
	 */
	public static function get(ContainerInterface $container){
		$builder = new RadApiInputGenerator();
		return $builder
		->setContainer($container)
		;
	}
	
	public function commit(RadApiInput $apiInput)
	{
		$name = $apiInput->getName();
		
		$class = $this->compileTwig("apiinput/class.php.twig", array(
				"input" => $apiInput,
				"ns" => $apiInput->getNamespace(),
				"name" => $apiInput->getName(),
		));
	    $dir = $this->getWorkingPath("Request\Api");
		$this->createDirs($dir);
	    $path = $dir. $name . ".php";
	    
		echo "Writing file " . $path . PHP_EOL;
		file_put_contents(
			$path,
			"<?php" . PHP_EOL . $class
			);
        
        return $this;
	}

}