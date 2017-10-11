<?php

namespace Mosaika\RadBundle\Core\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;

class RadServiceTypeGenerator {

	protected $bundle;
	
	protected $class;
	
	protected $namespace;
	
	protected $table;
	
	protected $vendor;
	
	protected $parent = "";
	
	
	/**
	 * @return the $vendor
	 */
	public function getVendor()
	{
		return $this->vendor;
	}
	
	/**
	 * @param field_type $vendor
	 */
	public function setVendor($vendor)
	{
		$this->vendor = $vendor;
	}
	
	public function commit(){
		$this->commitChain();
		$this->commitServiceType();
		return $this;
	}

	protected function commitChain(){
	
		$path = $this->getChainWorkingPath();
	

		//TODO Finire sotto
		$implFile = $path . $this->getClass() . "Impl.php";
		$clsFile = $path . $this->getClass() . ".php";
		$repoFile = $path . $this->getClass() . "Repository.php";
		$impl = $this->buildClassImpl();
		$cls = $this->buildClass();
		$repo = $this->buildRepository();
		if(!file_exists($path)){
			mkdir($path,664,true);
		}
		file_put_contents($implFile, $impl->__toString());
		if(!file_exists($clsFile)){
			file_put_contents($clsFile, $cls->__toString());
		}
		if(!file_exists($repoFile)){
			file_put_contents($repoFile, $repo->__toString());
		}
	}
	protected function commitBoooh(){

		$path = $this->getChainWorkingPath();

		//TODO Finire sotto
		$implFile = $path . $this->getClass() . "Impl.php";
		$clsFile = $path . $this->getClass() . ".php";
		$repoFile = $path . $this->getClass() . "Repository.php";
		$impl = $this->buildClassImpl();
		$cls = $this->buildClass();
		$repo = $this->buildRepository();
		if(!file_exists($path)){
			mkdir($path,664,true);
		}
		file_put_contents($implFile, $impl->__toString());
		if(!file_exists($clsFile)){
			file_put_contents($clsFile, $cls->__toString());
		}
		if(!file_exists($repoFile)){
			file_put_contents($repoFile, $repo->__toString());
		}
	}

	protected function getChainWorkingPath(){
		$path =$this->container->get('kernel')->locateResource('@' . $this->bundle)
		. "Chain" . DIRECTORY_SEPARATOR ;
		if($this->namespace)
			$path .= $this->namespace . DIRECTORY_SEPARATOR;
	
			$path = str_replace(array("\\","/"),DIRECTORY_SEPARATOR, $path);
	
			return $path;
	}

	protected function getServiceTypeWorkingPath(){
		$path =$this->container->get('kernel')->locateResource('@' . $this->bundle)
		. "Service" . DIRECTORY_SEPARATOR ;
		if($this->namespace)
			$path .= $this->namespace . DIRECTORY_SEPARATOR;
	
			$path = str_replace(array("\\","/"),DIRECTORY_SEPARATOR, $path);
	
			return $path;
	}
	

	/**
	 *
	 * @return \Mosaika\RadBundle\Core\Generator\RadServiceTypeGenerator
	 */
	public static function get(ContainerInterface $container){
		$builder = new RadServiceTypeGenerator();
		return $builder
		->setContainer($container)
		->clearFIelds();
	}
	/**
	 *
	 * @param ContainerInterface $container
	 * @return \Mosaika\RadBundle\Core\Generator\RadServiceTypeGenerator
	 */
	protected function setContainer(ContainerInterface $container){
		$this->container = $container;
		return $this;
	}

	/**
	 *
	 * @param string $bundle
	 * @return \Mosaika\RadBundle\Core\Generator\RadServiceTypeGenerator
	 */
	public function setBundle($bundle, $vendor=null){
		$this->bundle = $bundle;
		if($vendor)
			$this->vendor = $vendor;
			return $this;
	}
	
	public function getBundle()
	{
		return $this->bundle;
	}
	public function getFullNameSpace(){
		$res = "";
		if($this->vendor){
			$res .= $this->vendor . "\\";
		}
		if($this->bundle){
			$res .= $this->bundle. "\\";
		}
		if($this->namespace){
			$res .= $this->namespace . "\\" ;
		}
		return $res . "Service";
	}
	public function getFullClass(){
		return $this->getFullNameSpace() . "\\" . $this->getClass();
	}
	public function getClass()
	{
		return $this->class;
	}
	
	public function setClass($class)
	{
		$this->class = $class;
		return $this;
	}
	
	public function getNamespace()
	{
		return $this->namespace;
	}
	
	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
		return $this;
	}
	
	public function getParent()
	{
		return $this->parent;
	}
	
	public function setParent($parent)
	{
		$this->parent = $parent;
		return $this;
	}
	
}