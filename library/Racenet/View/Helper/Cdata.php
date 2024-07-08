<?php

/**
 * Racenet Library
 *
 * @category        Racenet
 * @package            Racenet_View
 * @subpackage    Racenet_View_Helper 
 */
class Racenet_View_Helper_Cdata
{
    /**
     * Pack content into a CDATA tag
     *
     * @param string
     * @return string
     */
    public function Cdata($data)
    {
        $parts = preg_split('/\]\]\>/', $data, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        
        return '<![CDATA['. implode(']]]]><![CDATA[>', $parts) .']]>';
    }
}
?>