<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Filter a human-readable racetime from millisecond notation
 * TODO: do the whole format stuff
 *
 */
class Racenet_Filter_Racetime implements Zend_Filter_Interface
{
    /*
     * Constants for format definitions
     */
    const FORMAT_COMMON             = 'racenet_timeformat_default (common datetime format)';
    const FORMAT_INGAME             = 'racenet_timeformat_ingame (minute to millisecond)';
    const FORMAT_FULLTEXT           = 'racenet_timeformat_fulltext (year to millisecond)';
    const FORMAT_SHORTTEXT          = 'racenet_timeformat_shorttext (minutes and above)';
    const FORMAT_DEFAULT            = self::FORMAT_INGAME;
    
    /*
     * Constants for format definition parts
     */
    const FP_YEAR                   = 0x01;
    const FP_MONTH                  = 0x02;
    const FP_DAY                    = 0x04;
    const FP_HOUR                   = 0x08;
    const FP_MINUTE                 = 0x10;
    const FP_SECOND                 = 0x20;
    const FP_MILLI                  = 0x40;

    /**
     * Constants defining the units in milliseconds
     */
    const MS_YEAR                   = 31104000000;
    const MS_MONTH                  = 2592000000;
    const MS_DAY                    = 86400000;
    const MS_HOUR                   = 3600000; 
    const MS_MINUTE                 = 60000;
    const MS_SECOND                 = 1000;
    const MS_MILLI                  = 1;
    
    const TRIM_LEADING_ZEROS        = true;
    const KEEP_LEADING_ZEROS        = false;
    
    /**
     * Format definition sets to filter the given time.
     * Using the format definitions and the format
     * definitions parts.
     * 
     * @var array Format definition sets
     */
    protected $_formats = array(
    
        self::FORMAT_COMMON   => array(
        
            self::FP_YEAR      => '%04d-',
            self::FP_MONTH     => '%02d-',
            self::FP_DAY       => '%02d ',
            self::FP_HOUR      => '%02d:',
            self::FP_MINUTE    => '%02d:',
            self::FP_SECOND    => '%02d',
        ),
        
        self::FORMAT_INGAME    => array(
        
           self::FP_MINUTE     => '%02d:',
           self::FP_SECOND     => '%02d.',
           self::FP_MILLI      => '%03d',
        ),
        
        self::FORMAT_FULLTEXT  => array(
        
            self::FP_YEAR      => array('%d years ', '1 year '),
            self::FP_MONTH     => array('%d months ', '1 month '),
            self::FP_DAY       => array('%d days ', '1 day '),
            self::FP_HOUR      => array('%d hours ', '1 hour '),
            self::FP_MINUTE    => array('%d minutes ','1 minute '),
            self::FP_SECOND    => array('%d seconds ','1 second '),
            self::FP_MILLI     => array('%d milliseconds','1 millisecond '),
        ),
        
        self::FORMAT_SHORTTEXT => array(
        
            self::FP_YEAR      => '%dY ',
            self::FP_MONTH     => '%dM ',
            self::FP_DAY       => '%dD ',
            self::FP_HOUR      => '%dh ',
            self::FP_MINUTE    => '%dm ',
        ),
    );
    
    /**
     * Association between format parts and their values in milliseconds
     * 
     * @var array Format parts in millseconds
     */
    protected $_fp2ms = array(
        self::FP_YEAR    => self::MS_YEAR,
        self::FP_MONTH   => self::MS_MONTH,
        self::FP_DAY     => self::MS_DAY,
        self::FP_HOUR    => self::MS_HOUR,
        self::FP_MINUTE  => self::MS_MINUTE,
        self::FP_SECOND  => self::MS_SECOND,
        self::FP_MILLI   => self::MS_MILLI,
    );
    
    /**
     * Weather to remove leading zeros or not
     *
     * @var boolean
     */
    protected $_trimZeroes = false;
    
    /**
     * The time unit which is should beeing passed to the filter
     *
     * @var interger
     */
    protected $_inputUnit = self::MS_MILLI;
    
    /**
     * Format preset 
     *
     * @var string
     */
    protected $_activeFormat = self::FORMAT_DEFAULT;
    
    /**
     * Setter for stripZeros (default = false)
     *
     * @param boolean $bool
     * @return Racenet_Filter_Racetime
     */
    public function setTrimZeros( $bool )
    {
        $this->_trimZeroes = (boolean)$bool;
        return $this;
    }
    
    /**
     * Set the input unit (default = 1 which is milliseconds)
     *
     * @param integer $unit
     * @return Racenet_Filter_Racetime
     */
    public function setInputUnit($milli)
    {
        // maybe let them play around with this instead of cathing?
        if (!in_array($milli, $this->_fp2ms)) {
            
            require_once 'Racenet/Filter/Racetime/Exception.php';
            throw new Racenet_Filter_Racetime_Exception('given input unit does not exists');
        }
        $this->_inputUnit = $milli;
        return $this;
    }
    
    
    /**
     * Setter for active format
     *
     * @param integer $formatId
     * @return Racenet_Filter_Racetime
     */
    public function setFormat($formatId)
    {
        if (!isset($this->_formats[$formatId])) {
            
            require_once 'Racenet/Filter/Racetime/Exception.php';
            throw new Racenet_Filter_Racetime_Exception('selected non existant format');
        }
        
        $this->_activeFormat = $formatId;
        return $this;
    }
    
    /**
     * Getter for Format Definition
     *
     * @return unknown
     */
    public function getFormat()
    {
        return $this->_formats[ $this->_activeFormat ];
    }
    
    /**
     * Adds a format-template for 
     *
     * @param integer $id
     * @param integer $format
     */
    public function addFormat( $formatId, $formatDefinitionSet )
    {
        if (isset($this->_formats[$formatId])) {
            
            require_once 'Racenet/Filter/Racetime/Exception.php';
            throw new Racenet_Filter_Racetime_Exception('Can not overwrite existing format with ID '. $formatId);
        }
        
        if (is_array($formatDefinitionSet)) {
            
            require_once 'Racenet/Filter/Racetime/Exception.php';
            throw new Racenet_Filter_Racetime_Exception('The given format definition set is invalid');
        }
        
        $this->_formats[$formatId] = $formatDefinitionSet;
    }

    /**
     * Filter the given time and return a human readable string.
     *
     * @param integer $time Time in milliseconds
     * @return string Formatted time
     */
    public function filter( $milli )
    {
        $backup = $milli;
        
        if (!array_key_exists( $this->_activeFormat, $this->_formats)) {
            require_once 'Racenet/Filter/Racetime/Exception.php';
            throw new Racenet_Filter_Racetime_Exception('no format selected or format is not available');
        }
        
        if (!is_numeric($milli)) {
            return $milli;
        }
        
        $milli = $milli / $this->_inputUnit;
        
        $lastFp = null;
        $lastVal = null;
        $rounded = array( 0 => null ); // FIXME: force associative array -.-
      
        foreach ($this->_formats[$this->_activeFormat] as $fp => $format) {
            $rounded[$fp] = floor($milli / $this->_fp2ms[$fp] );
            $milli -= $rounded[$fp] * $this->_fp2ms[$fp];

            if ($fp != self::FP_MILLI) {
                $lastFp  = $fp;
                $lastVal = $rounded[$fp];
            }
        }
        
        // round the last unit if following units are not shown
        if (null !== $lastVal && null !== $lastFp) {
            if ($this->_fp2ms[$lastFp] / 2 <= $milli) {
                $rounded[$lastFp]++; 
            }
        }
        
        $filtered = '';
        foreach($rounded as $fp => $val) {
            if( !$fp || !$val && $this->_trimZeroes )
               continue;
               
            $filtered .= $this->_formatPrint($this->_formats[$this->_activeFormat][$fp], $val); 
        }
        
       return $filtered;
    }
    
    /**
     * Select the proper formatstring and excute srpintf
     *
     * @param string $format
     * @param mixed $value
     * @return string
     */
    protected function _formatPrint($format, $value)
    {
        if (is_array($format)) {
            
            if ($value == 1) {
                
               $targetFormat = &$format[1];
               
            } else {
                
                $targetFormat = &$format[0];
            }
            
        } else {
            
            $targetFormat = &$format;
        }
                
        return sprintf($targetFormat, $value);
    }
}
