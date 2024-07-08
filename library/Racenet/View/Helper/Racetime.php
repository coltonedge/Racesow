<?php

/** Zend_View_Helper_Url.php */
require_once 'Zend/View/Helper/Url.php';

/**
 * Helper for formatting race times
 *
 * @package    Racenet_View
 * @subpackage Racenet_View_Helper
 */
class Racenet_View_Helper_Racetime extends Zend_View_Helper_Url
{
    public function Racetime($milliSeconds, $format = Racenet_Filter_Racetime::FORMAT_SHORTTEXT, $trimZeros = Racenet_Filter_Racetime::TRIM_LEADING_ZEROS)
    {
        
        $raceTime = new Racenet_Filter_Racetime;
        $raceTime->setTrimZeros($trimZeros)
                 ->setFormat($format);
                 
        return $raceTime->filter($milliSeconds);
    }
}
