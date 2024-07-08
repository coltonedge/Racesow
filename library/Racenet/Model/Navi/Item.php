<?php

/**
 * Enter description here...
 *
 */
class Racenet_Model_Navi_Item  extends Racenet_Model_Navi
{

    private $id;
    private $activator;
    private $href;
    private $title;
    private $src;
    private $srcActive;
    private $alt;
    private $content;
    
    /**
     * Enter description here...
     *
     * @param string $id
     * @return Racenet_Model_Navi_Item
     */
    public function setId( $id )
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Enter description here...
     *
     * @param string $id
     * @return Racenet_Model_Navi_Item
     */
    public function setActivator( $regex )
    {
        $this->activator = $regex;
        return $this;
    }
    
    /**
     * Enter description here...
     *
     * @param string $src
     * @return Racenet_Model_Navi_Item
     */
    public function setImage( $src )
    {
        $this->src = $src;
        return $this;
    }
    
    /**
     * Enter description here...
     *
     * @param string $src
     * @return Racenet_Model_Navi_Item
     */
    public function setActiveImage( $src )
    {
        $this->srcActive = $src;
        return $this;
    }
    
    /**
     * Enter description here...
     *
     * @param string $title
     * @return Racenet_Model_Navi_Item
     */
    public function setTitle( $title )
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Enter description here...
     *
     * @param string $url
     * @return Racenet_Model_Navi_Item
     */
    public function setHref( $href )
    {
        $this->href = $href;
        return $this;
    }
    
    /**
     * Enter description here...
     *
     * @param string $alt
     * @return Racenet_Model_Navi_Item
     */
    public function setAlt( $alt )
    {
        $this->alt = $alt;
        return $this;
    }
    
    /**
     * Enter description here...
     *
     * @param string $content innerHTML of the DOM Element
     * @return unknown
     */
    public function setContent( $content )
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getTitle()
    {    
        return $this->title;
    }
    
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getHref()
    {    
        return $this->href;
    }
    
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getImage()
    {    
        return $this->src;
    }
    
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getActiveImage()
    {    
        return $this->srcActive;
    }
    
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getAlt()
    {    
        return $this->alt;
    }
    
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Enter description here...
     *
     * @return boolean
     */
    public function isActive() 
    {
        return preg_match( '@^'. $this->activator .'$@', $_SERVER['REQUEST_URI'] );
    }
}

?>