<?php
namespace Mosaika\RadBundle\Model;

use Mosaika\RadBundle\Model\Query\RadQuery;

class RadApiInputParam{

	protected $name;
	
	protected $data;

	public function __construct($name, $data=array())
	{
		if(is_string($data)){
			$data = array("type" => $data);
		}
		$this->name = $name;
		$this->data = $data;
	}
	protected function _get($k, $default=null){
		return isset($this->data[$k]) ? $this->data[$k] : $default;
	}

	/**
	 * @return boolean
	 */
	public function isRequired(){
		return $this->_get("required");
	}

	/**
	 * @return boolean
	 */
	public function getType(){
		return $this->_get("type","mixed");
	}

	/**
	 * Get the value of data
	 */ 
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Set the value of data
	 *
	 * @return  self
	 */ 
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * Get the value of name
	 */ 
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the value of name
	 *
	 * @return  self
	 */ 
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}
}