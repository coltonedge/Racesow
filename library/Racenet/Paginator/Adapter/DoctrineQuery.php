<?php

class Racenet_Paginator_Adapter_DoctrineQuery implements Zend_Paginator_Adapter_Interface
{
    /**
     * Doctrine query object
     *
     * @var Doctrine_Query
     */
    private $_query;
    
    /**
     * Options to be passed to execute method
     *
     * @var array
     */
    private $_options;

    /**
     * Number of all items
     *
     * @var integer
     */
    private $_count;
    
    /**
     * Contructor
     *
     * @param Doctrine_Query $query
     * @package array $options
     */
    public function __construct(Doctrine_Query $query, $options = array())
    {
        $this->_query = $query;
        $this->_options = $options;
    }

    /**
     * Return the number of all items
     *
     * @return integer
     */
    public function count()
    {
        if ($this->_count === null) {
            
            $cntQuery = clone $this->_query; // never affect the original query!
            $cntQuery = $cntQuery->select('COUNT(*)')->removeQueryPart('orderby')->offset(0)->limit(1);
            
            if ($record = $cntQuery->fetchOne($this->_options)) {
                
                $this->_count = $record->COUNT;
            
            } else {
            
                $this->_count = 0;
            }
        }
        
        return $this->_count;
    }
    
    /**
     * Returns an array of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return $this->_query
            ->offset($offset)
            ->limit($itemCountPerPage)
            ->execute($this->_options);
    }
}