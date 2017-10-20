<?php
namespace Mosaika\RadBundle\Core\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Mosaika\RadBundle\Model\RadEntityRepository;
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
	/**
	 *
	 * @param RadQueryFilter $filter
	 */
	protected function evalFilterSql($filter,$key){
		$operator = str_replace("%","",$filter->getOperator());
		$left = $filter->getScope();
		$right = ":" . $key;
		return implode(" ",[$left,$operator,$right]);
	}
	/**
	 *
	 * @param RadQueryFilter $filter
	 */
	protected function evalFilterSource($filter,$key){
		$operator = $filter->getOperator();
		$value = "";
		$value = sprintf('$%s',$key);
		if(strpos($operator,"%")===0){
			$value = ('"%" . ' . $value);
		}
		if(strpos(strrev($operator),"%")===0){
			$value = ($value . ' . "%"');
		}
		return $value;
	}
	
	public function commit(RadEntityRepository $repository){
	    $entity = $repository->getEntity();
	    
	    $modelNs = new PhpNamespace($this->findNamespace("AbstractRepository"));
	    $repoNs = new PhpNamespace($this->findNamespace("Entity"));
	    
	    $modelClass = (new ClassType("Abstract" . $repository->getName(), $modelNs))
	    ->setAbstract(true)
	    ->setExtends("Doctrine\\ORM\\EntityRepository")
	    ;
	    
	    $modelClass->addConstant("ID", $entity->getDoctrineName());
	    
	    $repoClass = (new ClassType($repository->getName(), $repoNs))
	    ->setExtends($modelNs->getName() . "\\" . $modelClass->getName())
	    ;
	    
	    /**
	     * @var RadQuery $query
	     * @var RadQueryFilter $filter
	     */
	    foreach($repository->getQuery() as $query){
	    		$method = $modelClass->addMethod("query" . ucfirst(strtolower($query->getName())));
	    		$method->setVisibility("public");
	    		$method->addBody(sprintf('$queryBuilder = $this->createQueryBuilder("%s");', $query->getEntity()->getName()));
	    		
	    		foreach($query->getFilters() as $key => $filter){
//	    			if(!$filter->getScope() & RadQueryFilter::SOURCE_INPUT){
	    				$method->addParameter($key);
//	    			}
	    			$method->addBody(sprintf('$queryBuilder->andWhere("%s");',$this->evalFilterSql($filter,$key)));
	    			$method->addBody(sprintf('$queryBuilder->setParameter("%s",%s);',$key, $this->evalFilterSource($filter,$key)));
	    		}
	    		$method->addBody(sprintf('return $queryBuilder;'));
	    		//$method->addBody(sprintf('$query = $queryBuilder->getQuery();'));
	    		//$method->addBody(sprintf('return $query->getResult();'));
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
	    
	    if(!file_exists($repoPath)){
		    	echo "Writing file " . $repoPath . PHP_EOL;
		    	// Write Entity class
		    	file_put_contents(
	    			$repoPath,
	    			"<?php" . PHP_EOL . $repoNs . $repoClass
	    		);
	    }else{
		    	echo "Skipping file " . $repoPath . PHP_EOL;
	    }
	    
	}
    
}