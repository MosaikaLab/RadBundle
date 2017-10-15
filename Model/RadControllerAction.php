<?php
namespace Mosaika\RadBundle\Model;

class RadControllerAction{
    /**
     * @var string
     */
	protected $bundle;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $url;
    
    /**
     * @var string
     */
    protected $route;
    
    /**
     * 
     * @var string[]
     */
    protected $body = [];
    
    /**
     * 
     * @var string[]
     */
    protected $annotations;
    
    /**
     * 
     * @var RadController
     */
    protected $controller;
    
    public static function create($name,$controller,$url=null){
        return new RadControllerAction($name, $controller, $url);
    }
    
    public function __construct($name,$controller,$url=null){
        $this->name = $name;
        $this->url = $url;
        $this->controller = $controller;
        $this->annotations = [];
    }
    public function getFullUrl(){
	    	$url = "/" . $this->getUrl();
	    	if($this->controller->getBaseUrl()){
	    		$url = "/" . $this->controller->getBaseUrl() . "/" . $url;
	    	}
	    	$url = str_replace("//","/",$url);
	    	return $url;
    }
    public function getFullRoute(){
	    	$route = $this->getRoute();
	    	if($this->controller->getBaseRoute()){
	    		$route = $this->controller->getBaseRoute() . "_" . $route;
	    	}
	    	$route = str_replace("__","_",$route);
	    	return $route;
    }
    /**
     * @return string
     */
    public function getBundle(){
        return $this->bundle;
    }

    
    /**
     * @return mixed
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @param string $bundle
     * @return RadEntity
     */
    public function setBundle($bundle){
        $this->bundle = $bundle;
        return $this;
    }

    /**
     * @param mixed $name
     * @return RadEntity
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string
     */
	public function getUrl() {
		return $this->url ? $this->url : strtolower($this->name);
	}
	
	/**
	 * @param string $url
	 * @return RadControllerAction
	 */
	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}
	
    /**
     * @return string
     */
	public function getRoute() {
		return $this->route ? $this->route : strtolower($this->name);
	}
	
	/**
	 * @param string $url
	 * @return RadControllerAction
	 */
	public function setRoute($route) {
		$this->route = $route;
		return $this;
	}
	public function getBody() {
		return implode(PHP_EOL,$this->body);
	}
	/**
	 * @param string $body
	 * @return \Mosaika\RadBundle\Model\RadControllerAction
	 */
	public function setBody($body) {
		$this->body = [$body];
		return $this;
	}
	/**
	 * @param string $body
	 * @return \Mosaika\RadBundle\Model\RadControllerAction
	 */
	public function addBody($body) {
		$this->body[]= $body;
		return $this;
	}
	/**
	 * @return multitype:string 
	 */
	public function getAnnotations() {
		return $this->annotations;
	}

	/**
	 * @param string  $annotation
	 * @return RadControllerAction
	 */
	public function addAnnotation($annotation) {
		$this->annotations[] = $annotation;
		return $this;
	}

	
}

