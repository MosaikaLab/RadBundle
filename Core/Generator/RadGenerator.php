<?php
namespace Mosaika\RadBundle\Core\Generator;

use Mosaika\RadBundle\Model\RadEntity;
use Mosaika\RadBundle\Model\RadController;
class RadGenerator{
    protected $container;

    protected $entities;
    
    protected $controllers;

    protected $services;
    
    protected $cruds;
    
    protected $serviceTypes;
    
    protected $tablePrefix = "";
    
    protected $bundle;
    
    
    public function __construct($container){
        $this->container = $container;
        $this->entities = [];
        $this->controllers = [];
        
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
     * @return \Mosaika\RadBundle\Core\Generator\RadGenerator
     */
    public function addController($controller){
        $this->controllers[] = $controller;
        return $this;
    }

    /**
     * Add entity to generator
     * @param RadEntity $e
     * @return \Mosaika\RadBundle\Core\Generator\RadGenerator
     */
    public function addEntity($e){
    	$this->entities[] = $e;
    	if($this->tablePrefix){
    		$e->setTableName($this->tablePrefix . $e->getTableName());
    	}
    	return $this;
    }
    
    public function tableName($name){
        return $this->tablePrefix . $name;
    }
    
    public function commit(){
        $this->_commit(RadEntityGenerator::get($this->container), $this->entities);
        $this->_commit(RadControllerGenerator::get($this->container), $this->controllers);
    }
    
    public function _commit($generator, $collection){
        $generator->setBundle($this->bundle);
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
	
    
}

?>