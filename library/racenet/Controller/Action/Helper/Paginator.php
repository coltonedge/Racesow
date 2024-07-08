<?php

require_once( 'Zend/Controller/Action/Helper/Abstract.php' );

/**
 * Racenet_Pager
 *
 * @package       Racenet
 * @name                Racenet_Controller_Action_Helper_Paginator
 * @uses                 Zend_Controller_Action_Helper_Abstract
 */
class Racenet_Controller_Action_Helper_Paginator extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * The parameter which indicates the active page
     *
     * @var string
     */
    private $destParam;
    
    /**
     * Scheme for the gerenrated liks
     * :param will be replaced with $this->destParam
     * :$this->destParam will be replaced with the page number
     * ie /page/:number/
     * if no link scheme is given the link will be generated 
     * using the following scheme /:controller/:action/:param/:page/
     *
     * @var unknown_type
     */
    private $link;
    
    /**
     * Number of all pages 
     *
     * @var integer
     */
    private $numItems;
    
    /**
     * Defines how many items per page are beeing displayed
     *
     * @var integer
     */
    private $itemsPerPage;
    
    /**
     * The currently selected page
     *
     * @var integer
     */
    private $activePage;
    
    /**
     * The number of pages
     *
     * @var unknown_type
     */
    private $numPages;
    
    /**
     * How many links should we display?
     *
     * @var integer
     */
    private $linkSpectrum;
    
    /**
     * Show "go to first page" and "go to last page" ?
     *
     * @var boolean
     */
    private $showFirstLast;
    
    /**
     * Show "previous page" and "next page" ?
     *
     * @var boolean
     */
    private $showPrevNext;
    
    private $_domDest = '#inner_tube';
    
    
    /** Initialize values
     *
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function init()
    {
        $this->numItems                        = 0;
        $this->numPages                        = 0;
        $this->itemsPerPage                = 10;
        $this->linkSpectrum                = 10;
        $this->showFirstLast            = true;
        $this->autoHideFirstLast    = false;
        $this->showPrevNext                = true;
        $this->destController            = $this->getRequest()->getControllerName();
        $this->destAction                    = $this->getRequest()->getActionName();
        $this->destParam                    = 'page';
        $this->activePage                    = 1;
        $this->link                                = '/:controller/:action/:param/:page/';
        return $this;
    }
    
    /** Initialize values
     *
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function direct()
    {
        return clone $this;
    }
    
    /**
     * Set link
     *
     * @param string
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setLink( $link )
    {
        $this->link = $link;
        return $this;
    }
    
    /**
     * Set request param in destination link
     *
     * @param string $param Parameter which indicates the active page
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setDestParam( $param )
    {
        $this->destParam = $param;
        return $this;
    }
    
    /**
     * Set destController
     *
     * @param string $controller
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setDestController( $controller )
    {
        $this->destController = $controller;
        return $this;
    }
    
    /**
     * Set destAction
     *
     * @param string $controller
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setDestAction( $action )
    {
        $this->destAction = $action;
        return $this;
    }
    
    /**
     * Set numItems
     *
     * @param integer $num Number of items the pager has to handle
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setNumItems( $num )
    {
        $this->numItems = (integer)$num;
        return $this;
    }
    
    /**
     * Set itemsPerPage
     *
     * @param integer $num Number of items to show on one page
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setItemsPerPage( $num )
    {
        $this->itemsPerPage = (integer)$num;
        return $this;
    }
    
    /**
     * Set linkSpectrum
     *
     * @param inte $num Number of Links to display
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setLinkSpectrum( $num )
    {
        $this->linkSpectrum =(integer)$num;
        return $this;
    }
    
    /**
     * Set showFirstLast
     *
     * @param boolean $show
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setShowFirstLast( $show )
    {
        $this->showFirstLast = (boolean)$show;
        return $this;
    }
    
    /**
     * Set autoHideFirstLast
     * Has ho effect when $this->showFirstLast is false
     *
     * @param boolean $bool
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function autoHideFirstLast( $bool )
    {
        $this->autoHideFirstLast = (boolean)$bool;
        return $this;
    }
    
    /**
     * Set showPrevNext
     *
     * @param boolean $show
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setShowPrevNext( $show )
    {
        $this->showPrevNext = (boolean)$show;
        return $this;
    }
    
    /**
     * Set domDest
     *
     * @param string $dst
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function setDomDest( $dst )
    {
        $this->_domDest = $dst;
        return $this;
    }
    
    /**
     * Gets the offset for the SQL LIMIT expression 
     *
     * @return integer
     */
    public function getOffset()
    {
        return abs($this->activePage-1)*$this->itemsPerPage;
    }
    
    /**
     * Gets a MySQL-Formatted LIMIT expression
     *
     * @return string
     */
    public function getMysqlLimit()
    {
        return $this->getOffset() .",". $this->itemsPerPage;
    }
    
    /**
     * Get the currently selected page
     *
     * @return integer
     */
    public function getActivePage()
    {
        return $this->activePage;
    }
    
    /**
     * Enter description here...
     *
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function compute()
    {
        if( $this->numPages )
        {
            return $this;
        }
        
        $this->numPages = ceil( $this->numItems / $this->itemsPerPage );
        
        $this->activePage = max( 1, intval($this->getRequest()->getParam($this->destParam)) );
        
        if( $this->activePage > $this->numPages )
        {
            $this->activePage = $this->numPages;
        }
        
        if( $this->linkSpectrum%2==0 )
        {
            $this->leftOffset = $this->rightOffset = $this->linkSpectrum/2;
        }
        else
        {
            $this->leftOffset = $this->rightOffset =($this->linkSpectrum-1)/2;
        }
        
        if( ( $diff = $this->activePage-$this->leftOffset-1 ) < 0 )
        {
            $this->leftOffset += $diff;
            $this->rightOffset -= $diff;
        }
        
        if( ( $diff = $this->activePage+$this->rightOffset-$this->numPages ) > 0 )
        {
            $this->leftOffset += $diff;
            $this->rightOffset -= $diff;
        }
        return $this;
    }
    
    /**
     * Get links for all pages managed by the pager class
     *
     * @return array containing objects with information about each link
     */
    public function getAllLinks()
    {
        $this->compute();
        $links = array();
        for( $n = 1; $n <= $this->numPages; $n++ )
        {
            $obj = new stdClass();
            $obj->page = $n;
            $obj->url = $this->buildLink( $n );
            $obj->name = $n;
            $links[] = $obj;
        }
        return $links;
    }
    
    /**
     * Get links which are in the spectrum defined in this class
     *
     * @return array containing objects with information about each link
     */
    public function getLinks()
    {
        $this->compute();
        $links = array();
        
        for( $n = 1; $n <= $this->numPages; $n++ )
        {
            if( $n >= $this->activePage-$this->leftOffset && $n <= $this->activePage+$this->rightOffset )
            {
                $obj = new stdClass();
                $obj->page = $n;
                $obj->url = $this->buildLink( $n );
                $obj->name = $n;
                $links[] = $obj;
            }
        }
        return $links;
    }
    
    /**
     * Show "goto frist page" and "goto last page" links?
     *
     * @return boolean
     */
    public function showFirstLast()
    {
        if( $this->autoHideFirstLast )
        {
            return $this->showFirstLast && ($this->numPages > $this->linkSpectrum);
        }
        
        return $this->showFirstLast;
    }
    
    /**
     * Return true if first page is not in currently visible link spectrum
     *
     * @return boolean
     */
    public function firstVisible()
    {
        return ($this->activePage - $this->leftOffset <= 1);
    }
    
    /**
     *  Return true if last page is not in currently visible link spectrum
     *
     * @return boolean
     */
    public function lastVisible()
    {
        return ($this->activePage + $this->rightOffset >= $this->numPages);
    }
    
    /**
     * Show "goto previous page" and "goto last page" links?
     *
     * @return boolean
     */
    public function showPrevNext()
    {
        return $this->showPrevNext;
    }
    
    /**
     * Returns true if a previuos link is available
     *
     * @return boolean
     */
    public function prevAvailable()
    {
        return ($this->activePage-1 > 0);
    }
    
    /**
     * Returns true if a following link is available
     *
     * @return boolean
     */
    public function nextAvailable()
    {
        return ($this->activePage+1 <= $this->numPages);
    }
    
    /**
     * Get the number of the next page
     *
     * @return integer
     */
    public function getNextPage()
    {
        return min( $this->numPages, $this->activePage+1 );
    }
    
    /**
     * Get the number of the previous page
     *
     * @return integer
     */
    public function getPrevPage()
    {
        return max( 1, $this->activePage-1 );
    }
    
    /**
     * Get the number of pages
     *
     * @return integer
     */
    public function getNumPages()
    {
        return $this->numPages;
    }

    /**
     * Get the number of items per page
     *
     * @return integer
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }
    
    public function getDomDest()
    {
        return $this->_domDest;
    }
    
    /**
     * Builds the link for a pagenumber
     *
     * @todo use Zend Router?
     * @param integer $page the number of the page where the link should go to
     * @return string the complete link
     */
    public function buildLink( $destPage )
    {
        $link = str_replace( ':controller' ,$this->destController, $this->link );
        $link = str_replace( ':action' ,$this->destAction, $link );
        $link = str_replace( ':param' ,$this->destParam, $link );
        $link = str_replace( ':'. $this->destParam, $destPage, $link );
        return $link;
    }
}
