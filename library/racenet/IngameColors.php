<?php

/**
 * Class to convert quake/warasow ingame color tokens to HTML
 *
 * @category Racenet
 * @author Andreas Linden <zlx@gmx.de>
 */
class Racenet_IngameColors
{
    /**
     * Use generic scheme? (warsow)
     *
     */
    const GENERIC_SCHEME = 'Racenet_IngameColors_DefaultScheme';
    
    /**
     * The string to parse
     *
     * @var string
     */
    private $_string;
    
    /**
     * The selected color scheme
     *
     * @var string
     */
    private static $_scheme;
    
	/**
     * Use font tags instead of style attributes?
     *
     * @var boolean
     */
	private $_useFontTags = false;
	
    /**
     * Constructor
     *
     */
    public function __construct($string, $scheme = self::GENERIC_SCHEME, $useFontTags = false)
    {
        $this->_string = $string;
        $this->_useFontTags = $useFontTags;
		
        if ($scheme === self::GENERIC_SCHEME || $scheme === null) {
            
            self::$_scheme = new Racenet_IngameColors_DefaultScheme;
            
        } else {
        
            self::$_scheme = Zend_Registry::get('config')->ingameColors->get($scheme);
        }
    }
    
    /**
     * Output the formatted string
     *
     * @return Racenet_IngameColors
     */
    public function __toString()
    {
        return $this->_getHtml($this->_string);
    }

    /**
     * Get the colored string in html 
     *
     * @param string $nick
     * @return string
     */
    private function _getHtml()
    {
        $str = htmlspecialchars( $this->_string );
        $str = preg_replace_callback( "/(?<!(?<!\^)\^)\^([^^])((?:[^^]|\^{2})+)*/", array( $this, '_getColor' ), $str );
        if ("" == $str = trim($str)) {
            
           return $str;
        }

        $hex = self::$_scheme->get(7);
        
		if ($this->_useFontTags) {
		
			return $str;
		
		} else {
		
			return "<span style=\"color: $hex\">$str</span>";
		}
    }
    
    /**
     * Return text with one color
     *
     * @param array $hit
     * @return string
     */
    private function _getColor( $hit )
    {
        if(!isset($hit[2]) || !strlen($hit[2])) {
            
            return "";
        }
            
        $text = str_replace("^^", "^", $hit[2]);
        if (!$hex = self::$_scheme->get($hit[1])) {
            
            $hex = self::$_scheme->get(0);
        }
        
		if ($this->_useFontTags) {
		
			return "<font color=\"$hex\">$text</font>";
		
		} else {
		
			return '<span style="color: '. $hex .';">'. $text . '</span>';
		}
    }
}

/**
 * Default Scheme Class
 *
 */
class Racenet_IngameColors_DefaultScheme
{
    /**
     * Warsow ingame colors
     *
     * @var array
     */
    private $_colors = array(
       
        0 => "#000000",
        1 => "#ff0000",
        2 => "#00ff00",
        3 => "#fff000",
        4 => "#0000ff",
        5 => "#00ffff",
        6 => "#ff00ff",
        7 => "#ffffff",
        8 => "#ff6000",
        9 => "#666666"
    );
    
    /**
     * Getter
     *
     * @param integer $index
     * @return string
     */
    public function get($index)
    {
        if (array_key_exists($index, $this->_colors)) {
            
            return $this->_colors[$index];
        }
        
        return '';
    }
}
