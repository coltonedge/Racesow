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
 * @package    Racenet_Form
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */


/*
 * @see Zend_Form_Element_Password
 */
require_once 'Zend/Form/Element/Password.php';

/**
 * Racenet_Form_Element_Password
 * 
 * @category   Racenet
 * @package    Racenet_Form
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */
class Racenet_Form_Element_Password extends Zend_Form_Element_Password
{
    /**
     * Ignore-if-empty flag (used when retrieving values at form level)
     * @var bool
     */
    protected $_ignoreIfEmpty = false;
    
     /**
     * Set ignore-if-empty flag (used when retrieving values at form level)
     *
     * @param boolean $bool Status
     * @return Racenet_Form_Element_Password
     */
    public function setIgnoreIfEmpty($bool)
    {
        $this->_ignoreIfEmpty = (boolean) $bool;
        return $this;
    }
    
    /**
     * Get ignore-if-empty flag (used when retrieving values at form level)
     *
     * @return boolean
     */
    public function getIgnoreIfEmpty()
    {
        return $this->_ignoreIfEmpty;
    }
}

?>