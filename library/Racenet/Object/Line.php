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
 * @see Racenet_Object_Coordinate
 */
require_once 'Racenet/Object/Coordinate.php';

/**
 * Racenet_Object_Line
 * 
 * @category   Racenet
 * @package    Racenet_Object
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 * 
 * @uses       Racenet_Object_Coordinate
 */
class Racenet_Object_Line extends Racenet_Object_Abstract
{
    /**
     * Public properties
     *
     * @var array
     */
    protected $_props = array(
        "a" => null,
        "b" => null
    );
    
    /**
     * Constructor
     *
     * @param mixed $in
     */
    public function __construct($a = null, $b = null)
    {
        $this->a = self::toInstance($a, 'coordinate');
        $this->b = self::toInstance($b, 'coordinate');
    }
    
    /**
     * Compute and return the lines length if a
     * and b are both set. Otherwise returns null.
     *
     * @return float|null Length
     */
    public function getLength( $roundPrecision = null )
    {
        if( $this->a && $this->b && $this->a->isValid() && $this->b->isValid() )
        {
            $len = sqrt( ( pow($this->a->x - $this->b->x, 2) ) +
                         ( pow($this->a->y - $this->b->y, 2) ) +
                         ( pow($this->a->z - $this->b->z, 2) ) );
                         
            if( $roundPrecision !== null )
            {
               $len = round($len, (integer)$roundPrecision);
            }
            
            return $len;
        }
        else
        {
            return null;
        }
    }
    
    /**
     * Magic setter replacement
     *
     * @param string $name
     * @param mixed $value
     */
    protected function _set($name, $value)
    {
        $this->_props[$name] = self::toInstance($value, 'coordinate');
    }
}
