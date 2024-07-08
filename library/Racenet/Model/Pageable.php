<?php

require_once 'Racenet/Model/Abstract.php';

/**
 * Racenet_Model_Pageable
 * 
 * @category   Racenet
 * @package    Racenet_Model
 */

class Racenet_Model_Pageable extends Racenet_Model_Abstract {

    protected $_skip;
    protected $_limit;
    protected $_paginator;
    
    /**
     * Set the limit for SQL-limitation based on Racenet_Controller_Action_Helper_Paginator object
     *
     * @param Racenet_Controller_Action_Helper_Paginator $paginator
     */ 
    public function addPaginator( Racenet_Controller_Action_Helper_Paginator $paginator )
    { 
        $this->_paginator = $paginator->setNumItems( $this->count() )->compute();
        $this->limit( $this->_paginator->getItemsPerPage() );
        $this->skip( $this->_paginator->getOffset() );
        return $this;
    }
    
    /**
     * Set the number of postings to skip when selecting
     *
     * @param unknown_type $num
     * @return Racenet_Model_Pageable The class itsself
     */
    public function skip( $num )
    {
        $this->_skip = (integer)$num;
        return $this;
    }
    
    /**
     * Set how many items you want to select
     *
     * @param unknown_type $num
     * @return Racenet_Model_Pageable The class itsself
     */
    public function limit( $num )
    {
        $this->_limit = (integer)$num;
        return $this;
    }
    
    /**
     * Get SQL-query limitations
     *
     * @return string SQL LIMIT
     */
    protected function _addLimit( &$qry )
    {
        if( isset( $this->_skip ) && isset( $this->_limit ) )
        {
            $qry .= " LIMIT ". $this->_skip .", ". $this->_limit;
        }
        else if( isset( $this->limit ) )
        {
            $qry .= " LIMIT ". $this->_limit;
        }
    }
    
    /**
     * Enter description here...
     *
     * @return Racenet_Controller_Action_Helper_Paginator
     */
    public function getPaginator()
    {
        return $this->_paginator;
    }
    
    /**
     * @return integer
     */
    public function count()
    {
        require_once 'Racenet/Model/Pageable/Exception.php';
        throw new Racenet_Model_Pageable_Exception('the count() method in "'. get_class( $this ) .'" is not implement' );
    }
}