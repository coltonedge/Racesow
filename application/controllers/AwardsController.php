<?php

/**
 * AwardsController
 *
 * @uses Racenet_Controller_Action 
 */
class AwardsController extends Racenet_Controller_Action
{
    /**
     * indexAction
     *
     */
    public function indexAction()
    {
        $query = Doctrine_Query::create()
           ->from('Award')
           ->orderBy('date DESC')
           ->limit(20);
        
        $lastawards = $query->execute();
        $this->view->lastawards = $lastawards;
		$this->view->headTitle("Awards");
    }
}


