<?php

/**
 * This class encapsulates the basic functionality
 * behind the "Racenet Query Engine".
 *
 * @author soh-zolex
 */
abstract class Racenet_Ranking
{
    /**
     * PDO Database conenction
     *
     * @var unknown_type
     */
    protected $_pdo;

    /**
     * A possible message to be passed to the user
     *
     * @var string
     */
    protected $_message;
    
    /**
     * The current page to be displayed
     *
     * @var integer
     */
    protected $_page;

    /**
     * Behave like there was no paginator?
     *
     * @var boolean
     */
    protected $_singlePage = false;
    
    /**
     * Sorting order for the ranking
     *
     * @var string Column name
     */
    protected $_order;
    
    /**
     * Default sorting order
     *
     * @var string
     */
    protected $_defaultOrder;
    
    /**
     * Order directrion
     *
     * @var string
     */
    protected $_dir;
    
    /**
     * Default order direction for the current sorting order
     *
     * @var string
     */
    protected $_defaultDir;
    
    /**
     * Second order, for order with same scores
     *
     * @var string Coulumn name
     */
    protected $_secondOrder;
    
    /**
     * Pattern for items to highlight
     *
     * @var string
     */
    protected $_highlight;
    
    /**
     * Pagenumebrs with highlighted items
     *
     * @var array
     */
    protected $_highlightedPages = array();
    
    /**
     * When highlighting, the next page with matched items
     *
     * @var integer
     */
    protected $_nextMatchedPage;
    
    /**
     * When highlighting, the previous page with matched items
     *
     * @var integer
     */
    protected $_prevMatchedPage; 
    
    /**
     * Number of matched pages when highlighting
     *
     * @var integer
     */
    protected $_matchedPages;
    
    /**
     * Number of matched items when highlighting
     *
     * @var integer
     */
    protected $_matchedItems;
    
    /**
     * Pattern for standart search feature
     *
     * @var string
     */
    protected $_filter;
    
    /**
     * All columns allowed to order by
     *
     * @var array
     */
    protected $_columns = array();
    
    /**
     * The database column on which a search is performed
     *
     * @var string
     */
    protected $_searchColumn;
    
    /**
     * Column names which should be ordered ascending
     *
     * @var array
     */
    protected $_ascColumns = array();
    
    /**
     * Number of items to display on one page
     *
     * @var integer
     */
    protected $_itemsPerPage = 20;
    
    /**
     * Minimum number of items to display on one page
     *
     * @var integer
     */
    protected $_minItemsPerPage = 1;
    
    /**
     * Maximim number of items to display on one page
     *
     * @var integer
     */
    protected $_maxItemsPerPage = 100;
    
    /**
     * Number of all items
     *
     * @var integer
     */
    protected $_totalItemCount = 0;
    
    /**
     * Constructor
     *
     */
    protected function __construct()
    {
        $this->_pdo = Zend_Registry::get('doctrine')->getDbh();
        $this->init();
    }
    
    /**
     * Constructor replacement
     *
     */
    public function init()
    {
    }
    
    /**
     * Set the current page number
     *
     * @param integer $page
     * @return Racenet_Ranking
     */
    public function setPage($page)
    {
        $this->_page = (integer)$page;
        return $this;
    }
    
    /**
     * Set the number of items per page
     *
     * @param integer $num
     * @return Racenet_Ranking
     */
    public function setItemsPerPage($num)
    {
        $this->_itemsPerPage = min($this->_maxItemsPerPage, max($this->_minItemsPerPage, (integer)$num));
        return $this;
    }
    
    /**
     * Set the sorting order
     *
     * @param string $order
     * @return Racenet_Ranking
     */
    public function setOrder($order)
    {
        $order = strtolower($order);
        if (!in_array($order, $this->_columns)) {
            
            $order = $this->_defaultOrder;;
        }
        
        $this->_order = $order;
        
        if (in_array($this->_order, $this->_ascColumns)) {
                
           $this->_defaultDir = "ASC";
           
        } else {
                
            $this->_defaultDir = "DESC";
        }
        
        return $this;
    }
    
    /**
     * Get the sorting order
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Set order direction
     *
     * @param string $dir
     * @return Racenet_Ranking
     */
    public function setDir($dir)
    {
        $dir = strtoupper($dir);
        if (!in_array($dir, array("ASC", "DESC"))) {
            
            if (in_array($this->_order, $this->_ascColumns)) {
                
               $dir = "ASC";
               
            } else {
                
                $dir = "DESC";
            }
        }
        
        $this->_dir = $dir;
        return $this;
    }
    
    /**
     * Set pattern for highlighting
     *
     * @param string $highlight
     * @return Racenet_Ranking
     */
    public function setHighlight($highlight)
    {
        $this->_highlight = $highlight;
        return $this;
    }
    
    /**
     * Set pattern for display filter
     *
     * @param string $filter
     * @return Racenet_Ranking
     */
    public function setFilter($filter)
    {   
        $this->_filter = $filter;
        return $this;
    }
    
    /**
     * Getter for the message / notification
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->_message;
    }
    
    /**
     * Get the current value for the search field
     *
     * @return string
     */
    public function getSearchPreset($default = false)
    {
        if (!$default) {
            
            if ($this->_highlight) {
                
                return $this->_highlight;
            }
            
            if ($this->_filter) {
                
                return $this->_filter;
            }
        }
        
        return 'search term';
    }
    
    /**
     * Magic getter
     * 
     * @return mixed
     */
    public function __get($key)
    {
        if (property_exists($this, "_$key")) {

           return $this->{"_$key"};
        }
        
        throw new Exception('Trying to access non existant property '. get_class($this) .'->'. $key);
    }

    /**
     * Implement this function to build a really simple sql-parts array as we need alot of speed.
     * 
     * @example return array(
     *              'from' => 'player',
     *              'where' => 'id = 1',
     *          );
     *
     * @return array
     */
    protected abstract function _getQueryParts();
    
     /**
     * Simple and insecure method to build a mysql query
     * from a sql-parts-array. (better do not reuse ;P)
     *
     * @param array $query
     * @return string
     */
    private function _buildQuery($query)
    {
        $qry  = 'SELECT '. join(',', $query['select'] );
        $qry .= ' FROM '. $query['from'];
        
        if (isset($query['join']) && count($query['join'])) {
           $qry .= ' JOIN '. join(' JOIN ', $query['join']);
        }
        
        if (isset($query['leftjoin']) && count($query['leftjoin'])) {
           $qry .= ' LEFT JOIN '. join(' LEFT JOIN ', $query['leftjoin']);
        }
        
        if (isset($query['where']) && count($query['where'])) {
           $qry .= ' WHERE '. join(' AND ', $query['where']);
        }
        
        if (isset($query['groupby']) && count($query['groupby'])) {
           $qry .= ' GROUP BY '. join(',', $query['groupby']);
        }
        
        if (isset($query['orderby']) && count($query['orderby'])) {
           $qry .= ' ORDER BY '. join(',', $query['orderby']);
        }
        
        if (isset($query['limit'])) {
           $qry .= ' LIMIT '. $query['limit'];
        }

        // die($qry);
        
        return $qry;
    }
    
    /**
     * Fet additional information for an item
     *
     * @param stdClass &$item
     * @return void
     */
    protected function _addAdditionalInformation(&$item)
    {
    }
    
    /**
     * Compute everything and return the paginator instance
     *
     * @return Zend_Paginator
     */
    public function compute()
    {
        $cnt = 0;
        $seekPage = 0;
        $position = 0;
        $positionOffset = 0;
        $lastPosition = null;
        $lastPositionValue = null;
        $highlightAllowSetPage = false;
        $firstMatch = true;
        
        // remmeber if no page number was requested
        if (!$this->_page) {
            
            $this->_page = 1;
            $highlightAllowSetPage = true;
        }
        
        $query = $this->_getQueryParts();
        
        // FIXME: secondary sorting does not work?
        if ($this->_secondOrder && $this->_order != $this->_secondOrder) {

            $query['orderby'][] = $this->_secondOrder . ($this->_dir == "ASC" ?  " ASC" : " DESC");
        }
        
        // add name filter
        if ($this->_filter) {

            $query['where'][] = "$this->_searchColumn LIKE '%$this->_filter%'";
		}
		
		// hard limit
		// $query['limit'] = 20000;
        
        // fetch all items from the db as we compute the positions live
        $stmt = $this->_pdo->query($this->_buildQuery($query));
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);
        $this->_totalItemCount = count($items);
        
        // easy way to display everything on one page
        if ($this->_singlePage) {
            
            $this->_itemsPerPage = $this->_totalItemCount;
        }
        
        // when ordering with the direction which is the default one for
        // the current sorting order, count the positions ascending
        // (best first), otherwise count descending (best last)
        if ($this->_dir == $this->_defaultDir) {
            
            $start = 0;
            $add = 1;
            $seekPage = 0;
            $cntOffset = 0;
            
        } else {
            
            $start = $this->_totalItemCount - 1;
            $add = -1;
            $seekPage = ceil($this->_totalItemCount / $this->_itemsPerPage); // FIXME: why does map ranking require + 1 but this one doens't???
            $cntOffset = $this->_itemsPerPage - $this->_totalItemCount % $this->_itemsPerPage;
        }
        
        // Run through all items from best to worst untill we have computed
        // the current page. Note that same score, time or whatever the list is
        // beeing ordered by, means the same position. So we need to know
        // positions and scores from previous pages as well.
        // This is a single loop for all possible orders and directions. Never
        // touch this until you really know what you are doing!
        for (
                // initial value is first or last
                $n = $start;
                
                // run condition
                (
                    $add ==  1 && // when ordering "best first"
                    $n < $this->_totalItemCount && // never iterate more than available
                    (
                        $this->_highlight || // run to next matching page when highlighting...
                        $n <= $this->_page * $this->_itemsPerPage // ...otherwise run to the current page
                    )
                    
                ) || (
                
                    $add == -1 && // when ordering "worst first"
                    $n >= 0 && // never iterate more than available
                    (
                        $this->_highlight || // run to previous matching page when highlighting...   
                        $n >= ($this->_page - 1) * $this->_itemsPerPage // ...otherwise run to the current page
                    )
                );
                
                // run forward or backward
                $n += $add
        ) {

            // determine the start of a new page by the number of items
            $positionOnPage = (($cntOffset + $cnt++) % $this->_itemsPerPage);
            if (!$positionOnPage) {
                
                $seekPage += $add;
            }
            
            // if same score as last position it's the same positionas well 
            if ($items[$n]->{$this->_order} === $lastPositionValue) {    
                               
                $positionOffset++;
                $items[$n]->position = $lastPosition;
                
            // if not, assign the next position, regard previous multiple placements
            } else {
            
                $items[$n]->position = $position += $positionOffset + 1;
                $positionOffset = 0;
            }

            // assign the real placement
            $lastPosition = $items[$n]->position;
            $lastPositionValue = $items[$n]->{$this->_order};
            
            // highlighting also works when the normal pagination is used in between
            if ($this->_highlight && $items[$n]->match = (mb_stripos($items[$n]->{$this->_searchColumn}, $this->_highlight) !== false)) {
                
                // count number of matched items
                $this->_matchedItems++;

                if (!in_array($seekPage, $this->_highlightedPages)) {
                
                    $this->_highlightedPages[] = $seekPage;
                }
                
                // allow an initial highlight search to
                // find the first page to display.
                if ($highlightAllowSetPage) {
                    
                    $this->_page = $seekPage;
                    
                    // when ordering "worst first" stop allow
                    // setting the page, but we need ru run
                    // until the end in this case -.-
                    $highlightAllowSetPage = ($add == -1);
                }
            }
            
        }; // end of position compution loop
        

        // when highlighting determine previous and next matching pages
        if ($this->_highlight) {
            
            if (!$this->_matchedItems) {
                
                $this->_message = 'No players matched your search';
            
            } else {
            
                // when not ordering by default direction reverse the matched pages
                if ($add == -1) {
                    
                    $this->_highlightedPages = array_reverse($this->_highlightedPages);
                }
                
                // if current page does not has highlighted items...
                if (false === $index = array_search($this->_page, $this->_highlightedPages)) {
                    
                    // ..and no page is set, the next page is the first one with highlights
                    if (!$this->_page) {
                     
                        $this->_nextMatchedPage = $this->_highlightedPages[0];
                    
                    // otherwise we need to find the best matches from all highlighed pages
                    } else {
                        
                        foreach ($this->_highlightedPages as $page) {
                            
                            if ($page < $this->_page) {
                                
                                $this->_prevMatchedPage = $page;
                            
                            } else if ($page > $this->_page){
                                
                                $this->_nextMatchedPage = $page;
                                break;
                            }
                        }
                    }
                    
                // we're on a highlighted page
                } else {
    
                    // previous
                    if (isset($this->_highlightedPages[$index - 1])) {
                        
                        $this->_prevMatchedPage = $this->_highlightedPages[$index - 1];
                    }
                
                    // next
                    if (isset($this->_highlightedPages[$index + 1])) {
                        
                        $this->_nextMatchedPage = $this->_highlightedPages[$index + 1];
                    }
                }
            }
        }
        
        if ($this->_filter) {
            
            if (!$this->_totalItemCount) {
            
                $this->_message = 'No players matched your search';
            }
        }
        
        // add additional information to the items in the current page
        $start = ($this->_page - 1) * $this->_itemsPerPage;
        $end = min($this->_totalItemCount, $start + $this->_itemsPerPage);
        for ($n = $start; $n < $end; $n++) {
            
            $this->_addAdditionalInformation($items[$n]);
        }
        
        // pass ALL items to the paginator -.-
        $adapter = new Zend_Paginator_Adapter_Array($items);
        $this->_paginator = new Zend_Paginator($adapter);
        $this->_paginator->setItemCountPerPage($this->_itemsPerPage);
        $this->_paginator->setCurrentPageNumber($this->_page);
        
        return $this;
    }
}
