<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Filter for uploaded Maps
 *
 */
class Racenet_Filter_IncrementFileExt implements Zend_Filter_Interface
{
    /**
     * Get new name for the file if another file with the same name already exists.
     *
     * @param unknown_type $value
     * @return unknown
     */
    public function filter($value)
    {
        if( empty( $value ) )
            return;
        
        if( is_file($value) )
        {
            $filename  = basename($value);
            $directory = dirname($value);
            
            $inc = 1;
            do {
                $filename =  preg_replace( "/(?:\.\d+)?\.([^\.]+)$/", ".". $inc .".\\1", $filename );
            } while( file_exists($directory . DIRECTORY_SEPARATOR . $filename) && ++$inc );
            
            $value = $directory . DIRECTORY_SEPARATOR . $filename;
        }
        return $value;
    }
}

