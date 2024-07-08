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
 * Racenet_Object_Enum
 * 
 * @category   Racenet
 * @package    Racenet_Object
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 * 
 * @uses       Racenet_Object_Coordinate
 */
abstract class Racenet_Object_Enum
{
    private function __construct()
    {
    }
    
    final public function __set($name, $value)
    {
    }
    
    final public function __get($name)
    {
    }
    
    final function __call($name, $args)
    {
    }
}
