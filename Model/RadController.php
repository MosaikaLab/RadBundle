<?php
namespace Mosaika\RadBundle\Model;

class RadController extends RadClassable{
        
    /**
     * @var RadControllerAction[]
     */
    protected $actions;
    
    protected $baseUrl;
    
    protected $baseRoute;
    
    public function __construct($name, $namespace, $bundle=null){
        parent::__construct($name, $namespace, $bundle);
        $this->actions = [];
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
		return $this->baseUrl!=null ? $this->baseUrl : strtolower($this->name);	
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
		return $this->baseRoute ? $this->baseRoute : strtolower($this->name);
	}
	
    /**
     * @return \Mosaika\RadBundle\Model\RadController
     */
	public function setBaseRoute($baseRoute) {
		$this->baseRoute = $baseRoute;
		return $this;
	}
	
	
}

