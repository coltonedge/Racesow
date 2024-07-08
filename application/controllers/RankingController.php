<?php

/**
 * RankingController
 *
 * @uses       Racenet_Controller_Action 
 * @copyright  
 * @license    
 */
class RankingController extends Racenet_Controller_Action
{
    /**
     * indexAction
     *
     */
    public function indexAction()
    {   
		$this->view->headTitle("Player ranking");
	
        if ($this->getRequest()->isXmlHttpRequest()) {
        
            $this->layout->disableLayout();
        }

        $this->view->ranking = PlayerRanking::getInstance()
            ->setPage($this->_getParam('page'))
            ->setItemsPerPage(min(100, max(0, (integer)$this->_getParam('num', 20))))
            ->setOrder($this->_getParam('order'))
            ->setDir($this->_getParam('dir'))
            ->setFilter($this->_getParam('filter'))
            ->setHighlight($this->_getParam('hl'))
            ->compute();
    }
    
    /**
     * xmlAction - xml layout, no highlight
     *
     */
    public function xmlAction()
    {
        $this->layout->setLayout('xml');
    
        $this->view->ranking = PlayerRanking::getInstance()
            ->setPage(1)
            ->setItemsPerPage(min(100, max(0, (integer)$this->_getParam('num', 20))))
            ->setOrder($this->_getParam('order'))
            ->setDir($this->_getParam('dir'))
            ->setFilter($this->_getParam('filter'))
            ->compute();
    }
	
	/**
     * androidAction - android layout, no highlight
     *
     */
    public function androidAction()
    {
        $this->layout->disableLayout();
    
        $this->view->ranking = PlayerRanking::getInstance()
            ->setPage(1)
            ->setItemsPerPage(min(100, max(0, (integer)$this->_getParam('num', 20))))
            ->setOrder($this->_getParam('order'))
            ->setDir($this->_getParam('dir'))
            ->setFilter($this->_getParam('filter'))
            ->compute();
    }
}
