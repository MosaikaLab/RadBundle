<?php
namespace Mosaika\RadBundle\Model;

use Nette\PhpGenerator\Parameter;

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
     * @var Parameter[]
     */
    protected $arguments;
    
    /**
     * 
     * @var RadController
     */
    protected $controller;
    
    protected $addRoute=true;
    
    public static function create($name,$controller,$url=null){
        return new RadControllerAction($name, $controller, $url);
    }
    
    public function __construct($name,$controller,$url=null){
        $this->name = $name;
        $this->url = $url;
        $this->controller = $controller;
        $this->annotations = [];
        $this->arguments = [];
    }
    public function getFullUrl($suffix=null){
	    	$url = "/" . $this->getUrl();
	    	if($this->controller->getBaseUrl()){
	    		$url = "/" . $this->controller->getBaseUrl() . "/" . $url;
	    	}
	    	if($suffix){
	    		$url .= "/" . $suffix;
	    	}
	    	$url = str_replace("//","/",$url);
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
		return !is_null($this->url) ? $this->url : strtolower($this->name);
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
	/**
	 * @return multitype:\Nette\PhpGenerator\Parameter 
	 */
	public function getArguments() {
		return $this->arguments;
	}

	/**
	 * @param string $name
	 * @param string $type
	 * @param string $default
	 * @return RadControllerAction
	 */
	public function addArgument($name, $type=null, $default=null) {
		$arg = new Parameter($name);
		if(func_num_args() > 1)
			$arg->setTypeHint($type);
		if(func_num_args() > 2)
			$arg->setDefaultValue($default);
		$this->arguments[] = $arg;
		return $this;
	}
	/**
	 * @return boolean
	 */
	public function getAddRoute() {
		return $this->addRoute;
	}

	/**
	 * @param boolean $addRoute
	 * @return RadControllerAction
	 */
	public function setAddRoute($addRoute) {
		$this->addRoute = $addRoute;
		return $this;
	}



	
}

