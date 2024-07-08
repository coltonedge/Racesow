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
 * @package    Racenet_Validate
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */


/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';  


/**
 * Racenet_Validate_Context
 * 
 * @category   Racenet
 * @package    Racenet_Validate
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */
class Racenet_Validate_Context extends Zend_Validate_Abstract
{
    /**
     * Validation failure message key for when an error occurs and no external message is given
     */
    const ERR_DEFAULT = 'unmatchedContext';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::ERR_DEFAULT => "Value does not match the field '%context%'"
    );
    
    /**
     * Additional variables available for validation failure messages
     *
     * @var array
     */
    protected $_messageVariables = array(
        "context" => "_contextLabel"
    );
    
    /**
     * Label of the conext element on which the first error occured
     *
     * @var string
     */
    protected $_contextLabel;

    /**
     * Names of form elements to be validated in context with the value 
     * of the element this validator was added to
     *
     * @var array
     */
    protected $_contextElements;
    
    /**
     * Reference to the form which contains the elements to be used for validation
     *
     * @var Zend_Form
     */
    protected $_form;
    
    /**
     * Sets validator options
     *
     * @param  Zend_Form $form OPTIONAL
     * @param  string|array $context OPTIONAL Name(s) of the context form elements
     */
    public function __construct($form = null)
    {
        $this->setForm($form);
    }
    
    /**
     * Sets the form to lookup context elements in
     *
     * @param Zend_Form|null $form
     * @param string|null $form
     */
    public function setForm($form = null)
    {
        if( $form !== null && !$form instanceof Zend_Form )
        {
            require_once 'Racenet/Validate/Exception.php';
            throw new Racenet_Validate_Exception( 'The passed value has to be an instance of "Zend_Form"' );
        }
        $this->_form = $form;
        return $this;
    }
    
    /**
     * Adds a new form element which will be processed when validating the context(s)
     *
     * @param Zend_Form_Element|string $element Element instance or
     * @param array $userCall
     */
    /**
     * Adds a new form element which will be processed when validating the context(s)
     *
     * @param Zend_Form_Element|string $element Element instance or
     * @param array $userCall
     */
    public function addContextElement( $element, $messageTemplate = null )
    {
        if( $element instanceof Zend_Form_Element )
        {
            $this->_contextElements[] = array( $element, $messageTemplate );
        }
        else if( is_string( $element ) && $this->_form !== null )
        {
            $elementInstance = $this->_form->getElement($element);
            if($elementInstance === null)
            {
                require_once 'Racenet/Validate/Exception.php';
                throw new Racenet_Validate_Exception('The element "'. $element .'" does not exist in the given form');
            }
            $this->_contextElements[] = array( $elementInstance, $messageTemplate );
        }
        else
        {
            require_once 'Racenet/Validate/Exception.php';
            throw new Racenet_Validate_Exception('Invalid usage of addContextElement(). If passing an element name, the validator needs a form to get the element from');
        }
        return $this;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * @param  string $value
     * @param  array $context Dummy, for method-signature compatibility
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        foreach( $this->_contextElements as $element )
        {   
            if( $value != $element[0]->getValue() )
            {
                if( !empty( $element[1] ) )
                {
                    $this->setMessage( $element[1], self::ERR_DEFAULT );
                }
                $this->_contextLabel = $element[0]->getLabel();
                $this->_error( self::ERR_DEFAULT, $value );
                return false;
            }
        }
        return true;
    }
}
