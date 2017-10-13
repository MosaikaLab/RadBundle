<?php

namespace Mosaika\RadBundle\Factory;

use Symfony\Component\DependencyInjection\Container;
use Mosaika\RadBundle\Model\RadEntity;
use Doctrine\ORM\EntityRepository;
use Mosaika\RadBundle\Model\RadEntityRepository;
use Mosaika\RadBundle\Core\Generator\RadGenerator;

class RadFactory {
	/**
	 * @var Container
	 */
	protected $container;
	
	
	public function __construct(Container $container){
		$this->container = $container;
	}
	
	/**
	 * 
	 * @return \Mosaika\RadBundle\Core\Generator\RadGenerator
	 */
	public function getGenerator(){
		return new RadGenerator($this->container);
	}
	public function getBundleClass($bundle){
		$kernel = $this->container->get('kernel');
		$bundles = $kernel->getBundles();
		return new \ReflectionClass(get_class($bundles[$bundle]));
	}
	protected function setObjectFullClass($classable){
		$bundle = $classable->getBundle();
		$namespace = $classable->getNamespace();
		$name = $classable->getName();
		
		$fs = "\\" . $this->getBundleClass($bundle)->getNamespaceName();
		$fs .= "\\Entity\\" . $namespace . "\\" . $name;
		$classable->setFullClass(str_replace("\\\\","\\",$fs));
	}
	
	/**
	 * 
	 * @param RadEntity $entity
	 */
	public function createEntityRepository($entity){
		$repo = new RadEntityRepository($entity->getName() . "Repository", $entity->getNamespace(), $entity->getBundle());
		$this->setObjectFullClass($repo);
		$repo->setEntity($entity);
		$entity->setRepository($repo);
		return $repo;
	}
	
	public function createEntity($name, $namespace, $bundle=null){
		$e = new RadEntity($name, $namespace, $bundle);
		$this->setObjectFullClass($e);
		return $e;
	}
}

