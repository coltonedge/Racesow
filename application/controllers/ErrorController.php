<?php

/**
 * Controller which is called in case of calling a non-existant Controller and/or Action
 *
 * @uses       Racenet_Controller_Action 
 * @copyright  
 * @license    
 */
class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        $e = $this->_getParam('error_handler');

        
        switch ($e->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 Fehler -- Kontroller oder Aktion nicht gefunden
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->message = 'The requested page was not found.';
                break;
            default:
                   $this->view->message = $e['exception']->getMessage();
                break;
        }
    }
}

?>