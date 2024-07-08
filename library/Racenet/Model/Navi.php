<?php

/**
 * Racenet Library
 *
 * @category   Racenet
 * @package    Racenet_Navi
 * 
 */
class Racenet_Model_Navi
{
    private $items = array();
    
    /**
     * Enter description here...
     *
     * @param Racenet_Navi_Item $item
     */
    public function addItem( Racenet_Model_Navi_Item $item )
    {
        $this->items[] = $item;
        return $this;
    }
    
    /**
     * Enter description here...
     *
     * @return boolean
     */
    public function hasItems()
    {    
        return ( count( $this->items ) > 0 );
    }
    
    /**
     * Enter description here...
     *
     * @return array
     */
    public function getItems()
    {    
        return $this->items;
    }
    
/**
     * Enter description here...
     *
     * @return Racenet_Model_Navi_Item|null
     */
    public function getItem( $pos )
    {    
        if( !isset( $this->items[ $pos ] ) )
        {
            return null;
        }
        return $this->items[ $pos ];
    }
}

?>