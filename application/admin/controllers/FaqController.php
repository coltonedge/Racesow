<?php

/**
 * Controller for the Admin FAQ
 *
 * @uses       Racenet_Controller_Action 
 * @copyright  
 * @license    
 */
class Admin_FaqController extends Racenet_Controller_Action
{
   /**
     * Define acl for the controller
     *
     */
    protected $_acl = array(
        "actions" => array(
            "index" => AclRacenet::ADMIN_HELP
        ),
        "forward" => array("index", "application")
    );

    /**
     * indexAction
     *
     */
    public function indexAction()
    {
        $helpModel = new HelpOld;
        $this->view->help = $helpModel->showAnswer($this->_getParam("view"));
    }
    
    /**
     * saveAction
     *
     */
    public function saveAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $this->layout->disableLayout(true);
            $this->getHelper('viewRenderer')->setNoRender();
           
            $faqId = $this->_getParam('id');
            $question = $this->_getParam('question');
            $answer = $this->_getParam('answer');
            
            if ($question && $answer) {
            
                $faqTable = new FaqTableOld;
                $data = array(
                    "question" => $question,
                    "answer" => $this->_getParam('answer')
                );
                
                if ((integer)$faqId) {
                    
                    $faqTable->update($data, "id = $faqId");
                
                } else {
                    
                    $select = $faqTable->select()
                        ->order("position DESC")
                        ->limit("1");
                    $lastItem = $faqTable->fetchRow($select);
                    $data["position"] = $lastItem->position + 1;
                    
                    $faqId = $faqTable->insert($data);
                }
            }
            
            echo $faqId;
        }
        
    }
    
    /**
     * deleteAction
     *
     */
    public function deleteAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $this->layout->disableLayout(true);
            $this->getHelper('viewRenderer')->setNoRender();
            
            if ($faqId = (integer)$this->_getParam('id')) {
            
                $faqTable = new FaqTableOld;
                $faqTable->delete("id = $faqId");
                
                echo 1;
            }
        }
    }
    
    /**
     * saveorderAction
     *
     */
    public function saveorderAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $this->layout->disableLayout(true);
            $this->getHelper('viewRenderer')->setNoRender();
           
            $faq = $this->_getParam('faq');
            if (is_array($faq)) {
               
                $faqTable = new FaqTableOld;
                foreach ($faq as $pos => $faqId) {
                    
                    $faqTable->update(array("position" => $pos), "id = $faqId");
                }
            }
        }
    }
}

