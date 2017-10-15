<?php

namespace Mosaika\RadBundle\Model;

class RadClassable {
	protected $uses;
	
	protected $bundle;
	
	protected $name;
	
	protected $namespace;
	
	protected $extends;
	
	
	/**
	 * @var string Entity class, including namespace
	 */
	protected $fullClass;
	
	public function __construct($name,$namespace,$bundle){		
		$this->name = $name;
		$this->namespace = $namespace;
		$this->bundle = $bundle;
		$this->uses = [];
	}
	
	/**
	 * @return mixed
	 */
	public function getExtends() {
		return $this->extends;
	}

	/**
	 * @param mixed $extends
	 * @return RadClassable
	 */
	public function setExtends($extends) {
		$this->extends = $extends;
		return $this;
	}

	/**
	 * @return string Full class name, including namespace
	 */
	public function getFullClass(){
		return $this->fullClass;
	}
	/**
	 * Set the fullclass, provided by RadFactory::createEntity
	 * @param string $fullClass
	 * @return RadEntity
	 */
	public function setFullClass($fullClass){
		$this->fullClass = $fullClass;
		return $this;
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
	 * @return mixed
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * @param mixed $namespace
	 * @return RadClassable
	 */
	public function setNamespace($namespace) {
		$this->namespace = $namespace;
		return $this;
	}
	/**
	 * 
	 * @param string $use
	 * @return \Mosaika\RadBundle\Model\RadClassable
	 */
	public function addUse($use,$as=null){
		$this->uses[] = $use . ($as ? "@" . $as : "");
		return $this;
	}
	/**
	 * @return mixed
	 */
	public function getUses() {
		return $this->uses;
	}

}

