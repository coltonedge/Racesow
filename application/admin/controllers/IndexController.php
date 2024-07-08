<?php

/**
 * Controller for the Admin Homepage
 *
 * @uses       Racenet_Controller_Action 
 * @copyright  
 * @license    
 */
class Admin_IndexController extends Racenet_Controller_Action
{
    /**
     * Define acl for the controller
     *
     */
    protected $_acl = array(
        "controller" => AclRacenet::ANY_ADMIN,
        "forward" => array("index", "application")
    );

    /**
     * indexAction
     *
     */
    public function indexAction()
    {

    }
    
    public function mapsAction()
    {
        $table = new mapsTable();
        
        $sel = $table->select()
                     ->from("map", array( "id", "file AS path", "created"))
                     ->where("status = 'enabled'")
                     ->where("file != ''")
                     ->order("created DESC");
                    
        //Zend_Loader::loadClass('Zend_Paginator');
        //Zend_Loader::loadClass('Zend_Paginator_Adapter_DbSelect');
        
        $adapter = new Zend_Paginator_Adapter_DbSelect($sel);        
        $pager = new Zend_Paginator($adapter);
        $pager->setItemCountPerPage(20);
        
        if( null !== $this->_getParam("search") )
        {
            $currentPage = $pager->findPage( $this->_getParam("search") );
        }
        else
        {
            $currentPage = $this->_getParam("page", 1);
        }
        
        $pager->setCurrentPageNumber( $currentPage );
              
        $this->view->maps = array();
        foreach( $pager->getIterator() as $map )
        {
            $map->file = basename($map->path);
            if( $map->exists = is_file($map->path) )
            {
                $map->url = '/upload/maps/'. $map->file;
            }
            
            $this->view->maps[] = $map;
        }
        
        $this->view->paginator = $pager;
    }
}

