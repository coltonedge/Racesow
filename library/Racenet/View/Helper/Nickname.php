<?php

/**
 * Racenet Library
 *
 * @category        Racenet
 * @package            Racenet_View
 * @subpackage    Racenet_View_Helper 
 */
class Racenet_View_Helper_Nickname
{
    /**
     * Enter description here...
     *
     * @return Racenet_View_Helper_Nickname
     */
    public function Nickname($nickname)
    {
        return new Racenet_IngameColors($nickname);
    }
}
?>