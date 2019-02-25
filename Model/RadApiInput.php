<?php
namespace Mosaika\RadBundle\Model;

use Mosaika\RadBundle\Model\Query\RadQuery;

class RadApiInput extends RadClassable{

	protected $apiName;
	
	protected $apiMethod;
	
	protected $config;

	/**
	 * @return RadApiInputParam[]
	 */
	public function getInput(){
		$res = [];
		if(isset($this->config["input"])){
			foreach($this->config["input"] as $k => $v){
				$res[] = new RadApiInputParam($k, $v);
			}
		}
		return $res;
	}
	
	/**
	 * Get the value of config
	 */ 
	public function getConfig()
	{
		return $this->config;
	}
	

	/**
	 * Set the value of config
	 *
	 * @return  self
	 */ 
	public function setConfig($config)
	{
		$this->config = $config;

		return $this;
	}

	/**
	 * Get the value of apiMethod
	 */ 
	public function getApiMethod()
	{
		return $this->apiMethod;
	}

	/**
	 * Set the value of apiMethod
	 *
	 * @return  self
	 */ 
	public function setApiMethod($apiMethod)
	{
		$this->apiMethod = $apiMethod;

		return $this;
	}

	/**
	 * Get the value of apiName
	 */ 
	public function getApiName()
	{
		return $this->apiName;
	}

	/**
	 * Set the value of apiName
	 *
	 * @return  self
	 */ 
	public function setApiName($apiName)
	{
		$this->apiName = $apiName;

		return $this;
	}
}