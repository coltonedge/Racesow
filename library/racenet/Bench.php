<?php

/**
 * Racenet_Bench
 *
 */
class Racenet_Bench
{
    private $start;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->start = 0.0;
    }
    
    /**
     * get the microtime
     *
     * @return unknown
     */
    private function getmicrotime()
    { 
            list($usec, $sec) = explode(" ",microtime()); 
            return ((float)$usec + (float)$sec); 
    }
    
    /**
     * Start the timer
     *
     */
    public function start()
    {
         $this->start = $this->getmicrotime();
    }

    /**
     * Get differece from now to starttime
     *
     * @return unknown
     */
    public function diff()
    {
         return $this->getmicrotime() - $this->start;
    }
}
