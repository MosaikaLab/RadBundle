<?php
namespace Mosaika\RadBundle\Core\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Mosaika\RadBundle\Model\RadEntityRepository;
use Mosaika\RadBundle\Model\Query\RadQuery;
use Mosaika\RadBundle\Model\Query\RadQueryFilter;

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
	    
	    $modelClass = (new ClassType("Abstract" . $repository->getName(), $modelNs))
	    ->setAbstract(true)
	    ->setExtends("Doctrine\\ORM\\EntityRepository")
	    ;
	    
	    $repoClass = (new ClassType($repository->getName(), $repoNs))
	    ->setExtends($modelNs->getName() . "\\" . $modelClass->getName())
	    ;
	    
	    /**
	     * @var RadQuery $query
	     * @var RadQueryFilter $filter
	     */
	    foreach($repository->getQuery() as $query){
	    	echo "\tQUERY" . PHP_EOL;
	    		$method = $modelClass->addMethod("query" . ucfirst(strtolower($query->getName())));
	    		$method->setVisibility("public");
	    		$method->addBody(sprintf('$queryBuilder = $this->createQueryBuilder("%s");', $query->getEntity()->getName()));
	    		foreach($query->getFilters() as $filter){
	    			if(!$filter->getScope() & RadQueryFilter::SOURCE_INPUT){
	    				$method->addParameter($filter->getValue());
	    			}
	    			
	    		}
	    		$method->addBody(sprintf('$query = $queryBuilder->getQuery();'));
	    		$method->addBody(sprintf('return $query->getResult();'));
	    }
	    
	    $modelDir = $this->getWorkingPath("AbstractRepository");
	    if(!file_exists($modelDir)){
		    	mkdir($modelDir);
	    }
	    $repoDir = $this->getWorkingPath();
	    $modelPath = $modelDir. $modelClass->getName() . ".php";
	    $repoPath = $repoDir. $repository->getName() . ".php";
		    
	    
	    // Write Model class
	    echo "Writing file " . $modelPath . PHP_EOL;
	    file_put_contents(
	    		$modelPath,
	    		"<?php" . PHP_EOL . $modelNs . $modelClass
	    	);
	    
	    echo "Writing file " . $repoPath . PHP_EOL;
	    if(true || !file_exists($repoPath)){
		    	// Write Entity class
		    	file_put_contents(
	    			$repoPath,
	    			"<?php" . PHP_EOL . $repoNs . $repoClass
	    		);
	    }
	    
	}
    
}