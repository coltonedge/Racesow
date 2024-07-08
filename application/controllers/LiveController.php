<?php

/**
 * Controller for the Live-Page
 *
 * @uses       Racenet_Controller_Action 
 * @copyright  
 * @license    
 */
class LiveController extends Racenet_Controller_Action
{

    /**
     * Display onlineusers, teamspeak and all gameservers
     *
     */
    public function indexAction()
    {
		$this->view->headTitle("Live");
	
        // online users
        $userModel = new Onlineusers;
        $this->view->users = $userModel->getOnlineUsers();
        
        $this->view->personalRecords = Doctrine::getTable('PlayerMap')
            ->createQuery()
            ->orderBy('created DESC')
            ->limit(20)
            ->execute();
        
        // we will always render other actions, so render this one first
        $this->render();
        
        $config = new Zend_Config_Ini('/home/racesow/servers.ini');
       
        // gameservers
        foreach ($config as $id => $server) {    

        
            if ((integer)$server->enabled || (integer)$server->web) {
        
                $this->view->server = new Gameserver($config, $id);
                $this->render('server');
            }
        }
    }
    
    /**
     * Display a gameserver
     *
     */
    public function serverAction()
    {
        if ($this->getRequest()->isXmlHttpRequest() || $this->_getParam('layout') == '0') {
            
            $this->layout->disableLayout();
        }
        
        $id = $this->getRequest()->getParam('id');
        $config = new Zend_Config_Ini('/home/racesow/servers.ini');
        $this->view->server = new Gameserver($config, $id);
        $this->view->user = RacenetAccount::getInstance()->User;
		
		$this->view->headTitle("Live")
			->headTitle(strtoupper($id));
    }
    
    /**
     * Display xml for gameserver
     *
     */
    public function xmlAction()
    {
        $this->layout->setLayout('xml');
        $id = $this->getRequest()->getParam('id');
        $config = new Zend_Config_Ini('/home/racesow/servers.ini');
        $this->view->server = new Gameserver($config, $id);
    }
    
    /**
     * Enter description here...
     *
     */
    public function teamspeakAction()
    {
    }
}

?>