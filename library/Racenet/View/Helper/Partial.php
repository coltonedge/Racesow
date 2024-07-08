<?php

/**
 * Racenet_View_Helper_Partial
 * 
 * @author Andreas Linden <al@i22.de>
 */
class Racenet_View_Helper_Partial extends Zend_View_Helper_Partial
{
	/**
	 * Should we clone the view and clear the vars?
	 *
	 * @var boolean
	 */
	protected $_clone = true;
	
    /**
     * Allow to decide if we clone the view and clear the vars.
     *
     * @param string $name
     * @param string $module
     * @param string $model
     * @param boolean $clone
     * @return string
     * @author Andreas Linden <al@i22.de>
     */
    public function partial($name = null, $module = null, $model = null, $clone = true)
    {
        $this->_clone = (boolean)$clone;
        return parent::partial($name, $module, $model);
    }

    /**
     * Clone the current View
     *
     * @return Zend_View_Interface
     * @author Andreas Linden <al@i22.de>
     */
    public function cloneView()
    {
    	if (!$this->_clone) {
    		
    		return $this->view;
    	}
    	
        $view = clone $this->view;
        $view->clearVars();
        return $view;
    }
}
