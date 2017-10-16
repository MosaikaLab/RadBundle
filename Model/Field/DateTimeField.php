<?php
namespace Mosaika\RadBundle\Model\Field;


class DateTimeField extends DateField{
    
	/**
	 * 
	 * @param string $name
	 * @return \Mosaika\RadBundle\Model\Field\DateTimeField
	 */
    public static function create($name){
        return new self($name,"datetime");
    }
    
}

