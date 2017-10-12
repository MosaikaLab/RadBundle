<?php
namespace Mosaika\RadBundle\Core\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Mosaika\RadBundle\Model\RadEntityRepository;

class RadEntityRepositoryGenerator extends RadGeneratorBase {
	
	/**
	 *
	 * @return \Mosaika\RadBundle\Core\Generator\RadEntityRepositoryGenerator
	 */
	public static function get(ContainerInterface $container){
	    $builder = new RadEntityRepositoryGenerator();
		return $builder
		->setBaseNamespace("Entity")
		->setContainer($container)
		;
	}
	
	public function commit(RadEntityRepository $repository){
	    $entity = $repository->getEntity();
	    
	    $modelNs = new PhpNamespace($this->findNamespace("AbstractRepository"));
	    $repoNs = new PhpNamespace($this->findNamespace("Entity"));
	    
	    $modelClass = (new ClassType($entity->getName() . "Repository", $modelNs))
	    ->setAbstract(true)
	    ->setExtends("Doctrine\\ORM\\EntityRepository")
	    ;
	    
	    $repoClass = (new ClassType($entity->getName() . "Repository", $repoNs))
	    ->setExtends($modelNs->getName() . "\\" . $modelClass->getName())
	    ;
	    
	    $modelDir = $this->getWorkingPath("AbstractRepository");
	    if(!file_exists($modelDir)){
		    	mkdir($modelDir);
	    }
	    $repoDir = $this->getWorkingPath();
	    $modelPath = $modelDir. $modelClass->getName() . ".php";
	    $repoPath = $repoDir. $entity->getName() . "Repository.php";
		    
	    
	    // Write Model class
	    file_put_contents(
	    		$modelPath,
	    		"<?php" . PHP_EOL . $modelNs . $modelClass
	    	);
	    
	    if(!file_exists($repoPath)){
		    	// Write Entity class
		    	file_put_contents(
	    			$repoPath,
	    			"<?php" . PHP_EOL . $repoNs . $repoClass
	    		);
	    }
	    
	}
    
}