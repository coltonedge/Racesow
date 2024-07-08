<?php

/**
 * Controller for the Homepage
 *
 * @uses       Racenet_Controller_Action 
 * @copyright  
 * @license    
 */
class IndexController extends Racenet_Controller_Action
{
    /**
     * Fetches news from dorum-thread to display in view
     *
     */
    public function indexAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $this->layout->disableLayout();
        }

        $topics = Doctrine_Query::create()
            ->select('*')
            ->from('PhpbbTopics')
            ->where('forum_id = :forum_id')
            ->orderBy('topic_time DESC');
    
        $adapter = new Racenet_Paginator_Adapter_DoctrineQuery($topics, array('forum_id' => 1));
        $paginator = new Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(3);
        
        $this->view->paginator = $paginator;        
        
        if (!$this->getRequest()->isXmlHttpRequest()) {
        	
            $this->statsAction();
            $this->render('stats');
        }
                    
        $this->render('index');
    }
    
    /**
     * Controller for Server statistics
     *
     */
    public function statsAction()
    {
    }
	
	public function graphsAction()
	{
		$this->view->headTitle("Graphs");
	}
}

