<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Filter for md5 hash
 *
 */
class Racenet_Filter_Md5 implements Zend_Filter_Interface
{
    /**
     * Returns the md5 hash of the value
     *
     * @param unknown_type $value
     * @return unknown
     */
    public function filter($value)
    {
        if( empty( $value ) )
            return;
        
        return md5($value);
    }
}

