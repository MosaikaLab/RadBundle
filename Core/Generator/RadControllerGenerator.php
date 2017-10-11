<?php

namespace Mosaika\RadBundle\Core\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;

class RadControllerGenerator extends RadGeneratorBase{

    /**
     * 
     * @var string
     */
	protected $controllerSuperClass = \Symfony\Bundle\FrameworkBundle\Controller\Controller::class;
	
	
	/**
	 * 
	 * @var ControllerAction[]|ArrayCollection
	 */
	protected $actions;
	
	/**
	 * 
	 */
	public function __construct(){
		$this->actions = new ArrayCollection();
	}
	
	/**
	 *
	 * @return \Mosaika\RadBundle\Core\Generator\RadControllerGenerator
	 */
	public static function get(ContainerInterface $container){
		$builder = new RadControllerGenerator();
		return $builder
		->setBaseNamespace("Controller")
		->setContainer($container);
	}
	
	public function commit(){
		if(strpos($this->class, "Controller")===FALSE){
			$this->class .= "Controller";
		}
		$dir = $this->getWorkingPath();
		$path = $dir . $this->class . ".php";
		if(!file_exists($dir)){
			mkdir($dir,null,true);
		}
		$class = new GrammarClass();
		$class
		->setName($this->class)
		->setNamespace($this->getFullNameSpace())
		->addUse("Sensio\\Bundle\\FrameworkExtraBundle\\Configuration\\Route")
		->setExtend($this->normalizeNamespace($this->controllerSuperClass))
		;
		foreach($this->actions as $action){
		    $method = GrammarMethod::getInstance()
		    ->addContent($action->getContentString())
		    ->addModifier("public")
		    ->setName($action->getName() . "Action");
		    foreach($action->getRoute() as $routeName => $routePath){
		        $method->addAnnotation("Route", array(
		            $routePath,
		            "name" => $routeName
		        ));
		    }
		    $method->addArgument(
		    		GrammarProperty::getInstance()
		    		->setName("request")
		    		->setType(Request::class)
		    		->setTypeInArgument(true)
		    		);
		    $class->addMethod($method);
		}
		

		file_put_contents($path, $class);
	}

    public function getControllerSuperClass() {
        return $this->controllerSuperClass;
    }

    public function setControllerSuperClass($controllerSuperClass) {
        $this->controllerSuperClass = $controllerSuperClass;
        return $this;
    }

    public function getActions() {
        return $this->actions;
    }

    /**
     * 
     * @param unknown $actions
     * @return \Mosaika\RadBundle\Core\Generator\RadControllerGenerator
     */
    public function setActions($actions) {
        $this->actions = $actions;
        return $this;
    }
    /**
     * 
     * @param unknown $action
     * @return \Mosaika\RadBundle\Core\Generator\RadControllerGenerator
     */
    public function addAction($action) {
        $this->actions->add($action);
        return $this;
    }
    
	
}