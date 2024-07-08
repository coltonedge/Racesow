<?php

/** Zend_View_Helper_Url.php */
require_once 'Zend/View/Helper/Url.php';

/**
 * Helper for making easy links and getting urls that depend on the routes and router
 *
 * @package    Racenet_View
 * @subpackage Racenet_View_Helper
 * @author     Andreas Linden aka zolex
 */
class Racenet_View_Helper_Url extends Zend_View_Helper_Url
{
    /**
     * Options which will always be in the assembeled url, whatever happens
     *
     * @var array
     */
    static protected $_options = array();

    /**
     * Setter for strong URL options
     *
     * @param array $options
     * @return Racenet_View_Helper_Url
     */
    static public function setOptions(array $options)
    {
        self::$_options = $options;
    }
    
    /**
     * Setter for strong URL options
     *
     * @param array $options
     * @return Racenet_View_Helper_Url
     */
    static public function setOption($option, $value)
    {
        self::$_options[$option] = $value;
    }
    
    /**
     * Setter for strong URL options
     *
     * @param array $options
     * @return Racenet_View_Helper_Url
     */
    static public function removeOption($option)
    {
        if (isset(self::$_options[$option])) {
           self::$_options[$option] = null;
           unset(self::$_options[$option]);
        }
    }
    
    /**
     * Clear the forced options
     *
     * @return Racenet_View_Helper_Url
     */
    static public function clearOptions()
    {
        self::$_options = array();
    }
    
    /**
     * Generates an url given the name of a route and forced options
     
     * @param  array $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed $name The name of a Route to use. If null it will use the current Route
     * @param  bool $reset Whether or not to reset the route defaults with those provided
     * @return string Url for the link href attribute.
     */
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $urlOptions += self::$_options; // w00t :D
        return parent::url($urlOptions, $name, $reset, $encode);
    }
}
