<?php

abstract class Racenet_Model_Ranking extends Racenet_Model
{
    public function setModelDefinition()
    {
        $this->setMessage('')
             ->setPage(0)
             ->setSinglePage(false)
             ->setMinItemsPerPage(1)
             ->setMaxItemsPerPage(100)
             ->setItemsPerPage(20)
             ->setTotalItemCount(0)
             ->setColumns(array())
             ->setAscColumns(array())
              ->setDir('')
             ->setDefaultDir('')
             ->setOrder('')
             ->setDefaultOrder('')
              ->setSecondOrder('')
             ->setHighlight('')
             ->setHighlightedPages(array())
             ->setNextMatchedPage(0)
             ->setPrevMatchedPage(0)
             ->setMatchedPages(0)
             ->setMatchedItems(0)
             ->setFilter('')
             ->setPaginator(null)
             ->setWhere(array());
    }

    /**
     * Filter to table setter
     *
     * @param unknown_type $name
     */
    public function filterTable($name)
    {
        $this->columns = Doctrine::getTable($name)->getColumnNames();
    }
    
    /**
     * Filter for itemsPerPage setter
     *
     * @param integer $num
     */
    public function filterItemsPerPage($num)
    {
        return min($this->maxItemsPerPage, max($this->minItemsPerPage, (integer)$num));
    }

    /**
     * Filter for order setter
     *
     * @param string $order
     */
    public function filterOrder($order)
    {
        $order = strtolower($order);
        if (!in_array($order, $this->columns)) {

            $order = $this->defaultOrder;
        }

        if (in_array($this->order, $this->ascColumns)) {

            $this->defaultDir = "ASC";
             
        } else {

            $this->defaultDir = "DESC";
        }

        return $order;
    }
    

    /**
     * Filter for dir setter
     *
     * @param string $dir
     */
    public function filterDir($dir)
    {
        $dir = strtoupper($dir);
        if (!in_array($dir, array("ASC", "DESC"))) {

            if (isset($this->ascColumns[$this->order])) {

               $dir = $this->ascColumns[$this->order];

            } else {

                $dir = "DESC";
            }
        }

        return $dir;
    }

    /**
     * Get the current value for the search field
     *
     */
    public function getSearchPreset($default = false)
    {
        if (!$default) {

            if ($this->highlight) {

                return $this->highlight;
            }

            if ($this->filter) {

                return $this->filter;
            }
        }

        return 'search term';
    }

    /**
     * Compute that shit!
     *
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

        if (!$this->page) {

            $this->page = 1;
            $highlightAllowSetPage = true;
        }
        
        // build a really simple sql-parts array as we need alot of speed.
        $query = array(
            'from' => $this->table,
            'select' => array('*', '0 AS position'),
            'orderby' => array($this->order .' '. $this->dir),
        );

        // FIXME: secondary sorting does not work?
        if ($this->secondOrder && $this->order != $this->secondOrder) {

            $query['orderby'][] = $this->secondOrder . ($this->dir == "ASC" ?  " ASC" : " DESC");
        }

        // add name filter
        if ($this->filter) {

            $query['where'][] = "name LIKE '%$this->filter%'";
        }

        // fetch all items from the db as we compute the positions live
        $pdo = Zend_Registry::get('doctrine')->getDbh();
        $query = $this->_buildQuery($query);
        $stmt = $pdo->query($query);
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);
        $this->totalItemCount = count($items);

        // easy way to display everything on one page
        if ($this->singlePage) {

            $this->itemsPerPage = $this->totalItemCount;
        }

        // when ordering with the direction which is the default one for
        // the current sorting order, count the positions ascending
        // (best first), otherwise count descending (best last)
        if ($this->dir == $this->defaultDir) {

            $start = 0;
            $add = 1;
            $seekPage = 0;
            $cntOffset = 0;

        } else {

            $start = $this->totalItemCount - 1;
            $add = -1;
            $seekPage = ceil($this->totalItemCount / $this->itemsPerPage);
            $cntOffset = $this->itemsPerPage - $this->totalItemCount % $this->itemsPerPage;
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
                    $n < $this->totalItemCount && // never iterate more than available
                    (
                        $this->highlight || // run to next matching page when highlighting...
                        $n <= $this->page * $this->itemsPerPage // ...otherwise run to the current page
                    )

                ) || (

                    $add == -1 && // when ordering "best last"
                    $n >= 0 && // never iterate more than available
                    (
                        $this->highlight || // run to previous matching page when highlighting...
                        $n >= ($this->page - 1) * $this->itemsPerPage // ...otherwise run to the current page
                    )
                );

                // run forward or backward
                $n += $add
        ) {

            // determine the start of a new page by the number of items
            $positionOnPage = (($cntOffset + $cnt++) % $this->itemsPerPage);
            if (!$positionOnPage) {

                $seekPage += $add;
            }

            // if same score as last position it's the same positionas well
            if ($items[$n]->{$this->order} === $lastPositionValue) {

                $positionOffset++;
                $items[$n]->position = $lastPosition;

            // if not, assign the next position, regard previous multiple placements
            } else {

                $items[$n]->position = $position += $positionOffset + 1;
                $positionOffset = 0;
            }

            // assign the real placement
            $lastPosition = $items[$n]->position;
            $lastPositionValue = $items[$n]->{$this->order};

            // highlighting also works when the normal pagination is used in between
            if ($this->highlight && $items[$n]->match = (mb_stripos($items[$n]->simplified, $this->highlight) !== false)) {

                // count number of matched items
                $this->matchedItems++;

                if (!in_array($seekPage, $this->highlightedPages)) {

                    $this->highlightedPages[] = $seekPage;
                }

                if ($highlightAllowSetPage) {

                    $this->page = $seekPage;
                    $highlightAllowSetPage = ($add == -1);
                }
            }

        }; // end of position compution loop


        // when highlighting determine previous and next matching pages
        if ($this->highlight) {

            if (!$this->matchedItems) {

                $this->message = 'No players matched your search';

            } else {

                // when not ordering by default direction reverse the matched pages
                if ($add == -1) {

                    $this->highlightedPages = array_reverse($this->highlightedPages);
                }

                // if current page does not has highlighted items, the next page is the first one with highlights
                if (false === $index = array_search($this->page, $this->highlightedPages)) {

                    $this->nextMatchedPage = $this->highlightedPages[0];

                } else {

                    // previous
                    if (isset($this->highlightedPages[$index - 1])) {

                        $this->prevMatchedPage = $this->highlightedPages[$index - 1];
                    }

                    // next
                    if (isset($this->highlightedPages[$index + 1])) {

                        $this->nextMatchedPage = $this->highlightedPages[$index + 1];
                    }
                }
            }
        }

        if ($this->filter) {

            if (!$this->totalItemCount) {

                $this->message = 'No players matched your search';
            }
        }

        /* TODO: map details for current page
        // get additional information for items on teh current page
        $start = ($this->_page - 1) * $this->itemsPerPage;
        $end = min($this->_totalItemCount, $start + $this->itemsPerPage);
        for ($n = $start; $n < $end; $n++) {

            $id = (integer)$items[$n]->id;
            $stmt = $pdo->query("SELECT *, COUNT(id) AS num FROM award WHERE player_id = $id GROUP BY type");
            $items[$n]->awards = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        */

        // pass ALL items to the paginator -.-
        $adapter = new Zend_Paginator_Adapter_Array($items);
        $this->paginator = new Zend_Paginator($adapter);
        $this->paginator
            ->setItemCountPerPage($this->itemsPerPage)
            ->setCurrentPageNumber($this->page);

        return $this;
    }

    public function getFormAction(Zend_View $view)
    {

    }

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

        if ($this->where) {
            
            if (!isset($query['where'])) {
                
                $query['where'] = array();
            }
            
            $query['where'] = array_merge($query['where'], $this->where);
        }
        
        if (isset($query['where']) && count($query['where'])) {
           $qry .= ' WHERE '. join(' AND ', $query['where']);
        }

        if (isset($query['orderby']) && count($query['orderby'])) {
           $qry .= ' ORDER BY '. join(',', $query['orderby']);
        }

        if (isset($query['limit']) && isset($query['limit'])) {
           $qry .= ' LIMIT '. $query['limit'];
        }

        return $qry;
    }
}
