<?php

class NavigationTree extends Racenet_Model_Navi_Item
{
    /**
     * Singleton instance
     *
     * @var NavigationTree
     */
    static $__instance;
    
    /**
     * Singleton contructor
     *
     * @param null|Racenet_Model_Navi_Item $obj
     */
    private function __construct($obj = null)
    {
        if (!$obj instanceof Racenet_Model_Navi_Item) {
            
            $obj = $this;
        }
        
        if ($parentId = $obj->getId()) {
            
            $where = 'parent_id = '. $parentId;
                
        } else {
            
            $where = 'parent_id IS NULL';
        }

        $items = Doctrine::getTable('Navigation')
            ->createQuery()
            ->orderBy('position')
            ->where($where)
            ->execute();

        foreach( $items as $item )
        {
            $naviItem = new Racenet_Model_Navi_Item;
            $naviItem
                ->setId($item->id)
                ->setActivator($item->activator)
                ->setHref($item->ahref)
                ->setTitle($item->atitle)
                ->setImage($item->imgsrc)
                ->setActiveImage($item->imgsrcactive)
                ->setAlt($item->imgalt)
                ->setContent($item->adata);

            $obj->addItem($naviItem);
            $this->__construct($naviItem); 
        }
    }
    
    /**
     * Getter for the singleton instance
     *
     * @return NavigationTree
     */
    public static function getInstance()
    {
        if (!self::$__instance) {
            
            self::$__instance = new self;
        }
        
        return self::$__instance;
    }
}
