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
    
    public static function create($name,$url=null){
        return new RadControllerAction($name, $url);
    }
    
    public function __construct($name,$url=null){
        $this->name = $name;
        $this->url = $url;
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
}

