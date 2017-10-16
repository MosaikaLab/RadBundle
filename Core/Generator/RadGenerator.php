<?php
namespace Mosaika\RadBundle\Core\Generator;

use Mosaika\RadBundle\Model\RadEntity;
use Mosaika\RadBundle\Model\RadController;
use Mosaika\RadBundle\Model\RadEntityRepository;
use Mosaika\RadBundle\Model\Controller\RestController;
use Symfony\Component\DependencyInjection\Container;
class RadGenerator{
    protected $container;

    protected $entities;
    
    protected $controllers;
    
    protected $javascriptClients;
    
    protected $javascriptClientsDirectory;
    
    /**
     * @var RadEntityRepository[]
     */
    protected $repositories;

    protected $services;
    
    protected $cruds;
    
    protected $serviceTypes;
    
    protected $tablePrefix = "";
    
    protected $bundle;
    
    
    public function __construct($container){
        $this->container = $container;
        $this->entities = [];
        $this->controllers = [];
        $this->repositories = [];
        $this->javascriptClients = [];
    }
    /**
     * 
     * @return \Mosaika\RadBundle\Core\Generator\RadGenerator
     */
    public function setBundle($bundle){
        $this->bundle = $bundle;
        return $this;
    }
    /**
     * Add controller to generator
     * @param RadController $e
     * @param string $key 
     * @return \Mosaika\RadBundle\Core\Generator\RadGenerator
     */
    public function addController($controller,$key){
	    	if(!$controller->getBundle()){
	    		$controller->setBundle($this->bundle);
	    	}
        $this->controllers[$key] = $controller;
        return $this;
    }

    /**
     * Add entity to generator
     * @param RadEntity $entity
     * @param string $key 
     * @return \Mosaika\RadBundle\Core\Generator\RadGenerator
     */
    public function addEntity($entity,$key){
    	if(!$entity->getBundle()){
	    		$entity->setBundle($this->bundle);
	    	}
	    	$this->entities[$key] = $entity;
	    	if($this->tablePrefix){
	    		$entity->setTableName($this->tablePrefix . $entity->getTableName());
	    	}
	    	return $this;
    }
    
    public function tableName($name){
        return $this->tablePrefix . $name;
    }
    
    public function commit(){
    		$this->_commit(RadEntityGenerator::get($this->container), $this->entities);
    		$this->_commit(RadFormGenerator::get($this->container), $this->entities);
    		$this->_commit(RadControllerGenerator::get($this->container), $this->controllers);
	   	$this->_commit(RadEntityRepositoryGenerator::get($this->container), $this->repositories);
    }
    
    public function _commit($generator, $collection){
        $generator->setBundle($this->bundle);
echo PHP_EOL . PHP_EOL . "Committing " . get_class($generator) . PHP_EOL;
        foreach($collection as $c){
            $generator->commit($c);
        }
    }
    
	public function getTablePrefix() {
		return $this->tablePrefix;
	}
	
	public function setTablePrefix($tablePrefix) {
		$this->tablePrefix = $tablePrefix;
		return $this;
	}
	/**
	 * @return multitype:\Mosaika\RadBundle\Model\RadEntityRepository 
	 */
	public function getRepositories() {
		return $this->repositories;
	}
	
	/**
	 * @param \Mosaika\RadBundle\Model\RadEntityRepository  $repository
	 * @param string $key
	 * @return RadGenerator
	 */
	public function addRepository($repository, $key) {
		if(!$repository->getBundle()){
			$repository->setBundle($this->bundle);
		}
		$this->repositories[$key] = $repository;
		return $this;
	}
	/**
	 * @param RestController $restController
	 * @param string $key
	 * @return RadGenerator
	 */
	public function addJavascriptClient($restController, $key) {
		$this->javascriptClients[$key] = $restController;
		return $this;
	}
	/**
	 * @return mixed
	 */
	public function getJavascriptClientsDirectory() {
		return $this->javascriptClientsDirectory;
	}

	/**
	 * @param mixed $javascriptClientsDirectory
	 * @return RadGenerator
	 */
	public function setJavascriptClientsDirectory($javascriptClientsDirectory) {
		$this->javascriptClientsDirectory = $javascriptClientsDirectory;
		return $this;
	}
	/**
	 * 
	 * @return Container
	 */
	public function getContainer(){
		return $this->container;
	}

	
    
}

?>