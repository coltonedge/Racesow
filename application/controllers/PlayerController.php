<?php

/**
 * PlayerController
 *
 * @uses Racenet_Controller_Action 
 */
class PlayerController extends Racenet_Controller_Action
{
    /**
     * Get Player data
     *
     */
    public function indexAction()
    {        
        $player = Doctrine::getTable('Player')->find($this->_getParam('id'));
        $this->view->player = $player;
		$this->view->headTitle("Player")
			->headTitle($player->simplified);
    }

    /**
     * mapsAction
     *
     * @param boolean $recursionHook
     */
    public function mapsAction( $recursionHook = false )
    {
        if( $this->getRequest()->isXmlHttpRequest() ) {
            
            $this->_helper->layout->disableLayout();
        }
        
       if ($this->getRequest()->isXmlHttpRequest()) {

            $this->layout->disableLayout();
        }
        
        $this->view->player = Doctrine::getTable('Player')->find((integer)$this->_getParam('id'));
		
		$this->view->headTitle("Player")
			->headTitle($this->view->player->simplified);
		
        $ranking = PlayerMapRanking::getInstance()
            ->filterPlayer($this->_getParam('id'))
            ->setPage($this->_getParam('page'))
            ->setItemsPerPage($this->_getParam('num', 20))
            ->setOrder($this->_getParam('order'))
            ->setDir($this->_getParam('dir'))
            ->setFilter($this->_getParam('filter'))
            ->setHighlight($this->_getParam('highlight'));
            
        if ($weapons = $this->_getParam('weapons')) {
            
            if (!is_array($weapons)) {
                
                $weapons = preg_split('/\W/', $weapons);
            }
            
            foreach ($weapons as $weapon) {
                
                $ranking->filterWeapon($weapon);
            }
        }
            
        if ($types = $this->_getParam('types')) {
            
            if (!is_array($types)) {
                
                $types = preg_split('/\W/', $types);
            }
            
            foreach ($types as $type) {
                
                $ranking->filterType($type);
            }
        }
        
        $this->view->ranking = $ranking->compute();
    }
    
    /**
     * List of times made by a player on a map
     */
    public function mapracesAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $this->_helper->layout->disableLayout();
        }
	
		$props = array(
			':player_id' => $this->_getParam('id', 0),
			':map_id' => $this->_getParam('map', 0)
		);

		$q = Doctrine_Query::create()
			-> from('Race')
			-> where('player_id = :player_id AND map_id = :map_id', $props)
			-> orderBy('time');
		
		$this->view->races = $q->execute();
    }
    
    /**
     * pointsGraphAction
     *
     */
    public function pointshistoryAction()
    {
        $this->_helper->layout->setLayout('plain');
        
        $props = array
        (
            'type' => 'playerHistory',
            'playerId' =>  $this->_getParam('id', 0)
        );
        
        $test = new Model_Graph($props);
        $test->getGraph1();
    }
}

