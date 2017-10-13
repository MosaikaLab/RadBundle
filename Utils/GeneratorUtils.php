<?php
namespace Mosaika\RadBundle\Utils;

class GeneratorUtils{
	public static function dbToProperty($dbName){
		$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $dbName)));
		return $str;
	}
	public static function propertyToDb($input){
	    $matches = array();
	    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
	    $ret = $matches[0];
	    foreach ($ret as &$match) {
	        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
	    }
	    return implode('_', $ret);
	}
	public static function normalizeFullClass($c){
		if(($e=strpos($c,"\\"))!==FALSE){
			if($e>0){
				$c = "\\" . $c;
			}
		}
		return $c;
	}
	public static function dbToMethod($dbName, $prefix=""){
		$str = self::dbToProperty($dbName);
		
		if (!$prefix) {
			$str[0] = strtolower($str[0]);
		}
		return $prefix . $str;
	}
	public static function propertyToMethod($property, $prefix=""){
		$str = $property;
		
		if (!$prefix) {
			$str[0] = strtolower($str[0]);
		}
		return $prefix . ucfirst($str);
	}
}