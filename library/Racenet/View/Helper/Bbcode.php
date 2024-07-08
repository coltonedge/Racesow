<?php

/**
 * Racenet Library
 *
 * @category        Racenet
 * @package            Racenet_View
 * @subpackage    Racenet_View_Helper 
 */
class Racenet_View_Helper_Bbcode
{
    /**
     * Bbcode
     *
     */
    public function Bbcode($text)
    {
        return Racenet_Bbcode::getInstance()->parse(nl2br($text));
    }
}
