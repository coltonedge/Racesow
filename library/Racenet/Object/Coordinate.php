<?php

/**
 * Racenet Library, extension for Zend Framework
 *
 * LICENSE
 * 
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.warsow-race.net/library/LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@warsow-race.net so we can send you a copy immediately.
 * 
 * @category   Racenet
 * @package    Racenet_Object
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */


/**
 * @see Racenet_Object_Abstract
 */
require_once 'Racenet/Object/Abstract.php';


/**
 * Racenet_Object_Coordinate
 * 
 * @category   Racenet
 * @package    Racenet_Object
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 * 
 * TODO: maybe add more types of setting values
 */
class Racenet_Object_Coordinate extends Racenet_Object_Abstract
{
    /**
     * Public properties
     *
     * @var array
     */
    protected $_props = array(
        "x" => null,
        "y" => null,
        "z" => null
    );
    
    /**
     * Constructor
     *
     * @param mixed $in
     */
    public function __construct($in = null)
    {
        $className = __CLASS__;
        if ($in instanceof $className)
        {
            $this->copy($in);
        }
        else if (is_string($in))
        {
            $this->fromString($in);
        }
        else if ($in !== null)
        {
            require_once 'Racenet/Object/Coordinate/Exception.php';
            throw new Racenet_Object_Coordinate_Exception('invalid value passed to constructor');
        }
    }
    
    /**
     * Add up another coordinate and return the result
     *
     * @param mixed $other
     * @return Racenet_Object_Coordinate
     */
    public function add($other)
    {
        $other = self::toInstance($other, __CLASS__);
        $new = clone($this);
        $new->x += $other->x;
        $new->y += $other->y;
        $new->z += $other->z;
        return $new;
    }
    
    /**
     * Magic setter replacement
     *
     * @param string $name
     * @param mixed $value
     */
    protected function _set($name, $value)
    {
        $this->_props[$name] = (integer)$value;
    }
    
    /**
     * Set the coordinates using another instance of the class
     *
     * @param string $string
     * @return Racenet_Parser_Bsp_Entity_Origin
     */
    public function fromString( $string )
    {
        if( preg_match_all("/(?:([xXyYzZ])\s*.\s*)*([\-\+\d]+).*?/", $string, $coords) ) {
            
            $pos = 0;
            foreach ($coords[1] as $index => $coord)
            {
                $pos++;
                if (!in_array($coord, array_keys($this->_props))) {
                    
                    switch ($pos) {

                        case 1:
                            $coord = 'x';
                            break;
                        case 2:
                            $coord = 'y';
                            break;
                        case 3:
                            $coord = 'z';
                            break;
                    }
                }
                
                $this->_props[$coord] = $coords[2][$index];
            }
        }
        return $this;
    }
    
    /**
     * Validate the coordinate
     *
     * @return boolean
     */
    public function isValid()
    {
        return ( $this->x !== null && $this->y !== null && $this->z !== null );
    }
    
    /**
     * Convert into string
     *
     * @return string x,y,z
     */
    public function __toString()
    {
        return $this->x .','. $this->y .','. $this->z;
    }
}
