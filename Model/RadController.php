<?php
namespace Mosaika\RadBundle\Model;

class RadController{
    /**
     * @var string
     */
	protected $bundle;
	
    /**
     * @var string
     */
    protected $name;
        
    /**
     * @var RadControllerAction[]
     */
    protected $actions;
    
    protected $baseUrl;
    
    protected $baseRoute;
    
    public function __construct($name, $namespace, $bundle=null){
        $this->name = $name;
        $this->namespace = $namespace;
        $this->bundle = $bundle;
    }
    
    /**
     * @param RadControllerAction $action
     * @return \Mosaika\RadBundle\Model\RadController
     */
    public function addAction($action){
    	$this->actions[] = $action;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getBundle(){
        return $this->bundle;
    }

    /**
     * @return string
     */
    public function getTableName(){
        return $this->tableName;
    }
    
    /**
     * @return mixed
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @param string $bundle
     * @return RadController
     */
    public function setBundle($bundle){
        $this->bundle = $bundle;
        return $this;
    }

    /**
     * @param mixed $name
     * @return RadController
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return \Mosaika\RadBundle\Model\RadControllerAction[]
     */
	public function getActions() {
		return $this->actions;
	}
	
	/**
	 * @param RadControllerAction $actions
	 * @return RadController
	 */
	public function setActions($actions) {
		$this->actions = $actions;
		return $this;
	}
	
	/**
	 * 
	 */
	public function getBaseUrl() {
		return $this->baseUrl;
	}
	
    /**
     * @return \Mosaika\RadBundle\Model\RadController
     */
	public function setBaseUrl($baseUrl) {
		$this->baseUrl = $baseUrl;
		return $this;
	}
	
	/**
	 * 
	 */
	public function getBaseRoute() {
		return $this->baseRoute;
	}
	
    /**
     * @return \Mosaika\RadBundle\Model\RadController
     */
	public function setBaseRoute($baseRoute) {
		$this->baseRoute = $baseRoute;
		return $this;
	}
	
	
}

