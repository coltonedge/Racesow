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
 * @see Zend_Form
 */
require_once 'Zend/Form.php';


/**
 * @category   Racenet
 * @package    Racenet_Form
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 *
 */
class Racenet_Form extends Zend_Form
{
    /**
     * Elemnts which are supposed to be decorated with buttonDecorators
     * Add them with addButtonElement
     *
     * @var unknown_type
     */
    protected $_buttonElements = array();

    /**
     * Default Formelement Decorators
     *
     * @var array
     */
    public $elementDecorators = array(
        'ViewHelper',
        'Errors',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array('label', array('tag' => 'td')),
        //array('description', array('tag' => 'td')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    );
    
    /**
     * Button Element Decorators
     *
     * @var array
     */
    public $buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'button', 'colspan' => '2')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    );

    /**
     * Form Decorators
     *
     * @var unknown_type
     */
    public $formDecorators = array(
        'FormElements',
        array('HtmlTag', array('tag' => 'table', 'class' => 'formz')),
        'Form',
    );
    
    /**
     * Constructor
     *
     * @param array $props
     * @param array|null $options
     */
    public function __construct( $props = array(), $options = null )
    {
        $this->addPrefixPath('Racenet_Form_Element', 'Racenet/Form/Element', 'element' );
                
        foreach ( $props as $key => $value )
        {
            if ( property_exists( $this, '_'. $key ) )
            {
                $this->{'_'. $key} = $value;
            }
            else
            {
                require_once 'Racenet/Form/Exception.php';
                throw new Racenet_Form_Exception( "The property '\$_$key' (passed by props array) does not exist in ". get_class($this) ."." );
            }
        }
        
        parent::__construct($options);        
    }
    
    /**
     * automatically add elements in functions beginning with elem
     *
     */
    public function autoAddElements($filter = null)
    {
        $methods = get_class_methods( $this );
        foreach( $methods as $method )
        {
            if( substr($method, 0, 4) == 'elem' )
            {
                if( is_string($filter) )
                {
                    if( substr($method, 0, (4 + strlen($filter))) != 'elem'. $filter )                     
                    continue;
                }
                $elem = $this->$method();
                if( in_array( $elem->getAttrib('helper'), array('formSubmit', 'formButton')) )
                {
                    $this->addButtonElement( $elem );
                }
                else
                {
                    $this->addElement( $elem );
                }
            }
        }
    }
    
     /**
     * Add a new Button element which will automatically det decorated
     * with the default buttonDecorators
     *
     * $element may be either a string element type, or an object of type 
     * Zend_Form_Element. If a string element type is provided, $name must be 
     * provided, and $options may be optionally provided for configuring the 
     * element.
     *
     * If a Zend_Form_Element is provided, $name may be optionally provided, 
     * and any provided $options will be ignored.
     * 
     * @param  string|Zend_Form_Element $element 
     * @param  string $name 
     * @param  array|Zend_Config $options 
     * @return Zend_Form
     */
    public function addButtonElement($element, $name = null, $options = null)
    {
        $this->_buttonElements[] = $element->getName();
        parent::addElement($element, $name = null, $options = null);
    }
    
    /**
     * Remove element
     * 
     * @param  string $name 
     * @return boolean
     */
    public function removeElement($name)
    {
        parent::removeElement($name);
        if( $i = array_search($name, $this->_buttonElements) )
        {
            unset( $this->_buttonElements[$i] );
        }
    }
    
    /**
     * Loads the decoratos for the form
     *
     */
    public function loadDefaultDecorators()
    {
        $this->setDecorators($this->formDecorators);
        $this->setElementDecorators($this->elementDecorators, $this->_buttonElements, false);
        $this->setElementDecorators($this->buttonDecorators, $this->_buttonElements, true);
    }
    
    /**
     * Retrieve all form element values
     * 
     * @param  bool $suppressArrayNotation
     * @return array
     */
    public function getValues($suppressArrayNotation = false)
    {
        $values = array();
        foreach ($this->getElements() as $key => $element) {
            if ($element->getIgnore())
                continue;

            $tmp = $element->getValue();
            if ( empty($tmp) && method_exists($element, 'getIgnoreIfEmpty') && $element->getIgnoreIfEmpty() )
                continue;
            
            $values[$key] = $tmp;
        }
        
        foreach ($this->getSubForms() as $key => $subForm) {
            $fValues = $this->_attachToArray($subForm->getValues(true), $subForm->getElementsBelongTo());
            $values = array_merge($values, $fValues);
        }

        if (!$suppressArrayNotation && $this->isArray()) {
            $values = $this->_attachToArray($values, $this->getElementsBelongTo());
        }

        return $values;
    }
}