<?php

/** Zend_View_Helper_Url.php */
require_once 'Zend/View/Helper/Url.php';

/**
 * Helper for formatting race times
 *
 * @package    Racenet_View
 * @subpackage Racenet_View_Helper
 */
class Racenet_View_Helper_NumberFormat extends Zend_View_Helper_Url
{
    public function NumberFormat($number)
    {
        return number_format($number, null, null, ',');
    }
}
