<?php

/**
 * Form for requesting nickname merges
 *
 */
class Form_Nickmerge extends Racenet_Form
{
    public $playerFrom;
    public $playerTo;
    
    /**
     * Constructor
     *
     * @param array|null $options
     */
    public function init()
    {
        $this->addElement($this->inputFrom());
        $this->addElement($this->inputTo());
        $this->addElement($this->inputReason());
        $this->addElement($this->inputSubmit());
    }
    
    /**
     * Creates the From-Nickname input
     *
     * @return Zend_Form_Element_Text
     */
    private function inputFrom()
    {
        $notEmpty = new Zend_Validate_NotEmpty();
        $notEmpty->setMessage('Please enter a nickname');
        
        return $this->createElement('text', 'nick_from')
            ->setLabel('From')
            ->setRequired(true)
            ->addValidator($notEmpty);
    }
    
    /**
     * Creates the To-Nickname input
     *
     * @return Zend_Form_Element_Text
     */
    private function inputTo()
    {
        $notEmpty = new Zend_Validate_NotEmpty();
        $notEmpty->setMessage('Please enter a nickname');
        
        return $this->createElement('text', 'nick_to')
            ->setLabel('To')
            ->setRequired(true)
            ->addValidator($notEmpty);
    }
    
    /**
     * Creates the reason input
     *
     * @return Zend_Form_Element_Textarea
     */
    private function inputReason()
    {
        $notEmpty = new Zend_Validate_NotEmpty();
        $notEmpty->setMessage('Please enter a nickname');
        
        return $this->createElement('textarea', 'reason')
            ->setLabel('Detailed reason')
            ->setRequired(true)
            ->addValidator($notEmpty);
    }
    
    /**
     * Creates the submit button
     *
     * @return Zend_Form_Element_Submit
     */
    private function inputSubmit()
    {
        return $this->createElement('submit', 'check')
          ->setLabel('Send request');
    }
    
    /**
     * Custom validation
     *
     * @param array $data
     * @return boolean
     */
    public function isValid($data)
    {
        if (!$isValid = parent::isValid($data)) {
            
            return false;
        }
        
        $from = $this->getValue('nick_from');
        $to = $this->getValue('nick_to');
        
        if ($from == $to) {
            
            $this->getElement('nick_to')->addError('Can not merge to the same player');
            return false;
        }
        
        if (!$this->playerFrom = Doctrine::getTable('Player')->findOneBySimplified($from)) {
            
            $this->getElement('nick_from')->addError('Player was not found');
            $isValid = false;
            
        } else if ($this->playerFrom->hasIngameLinkage()) {
                
            $this->getElement('nick_from')->addError('Can not merge from a player with ingame-linkage');
            $isValid = false;
            
        } else if ($this->playerFrom->hasNickmergeRequested()) {
            
            $this->getElement('nick_from')->addError('There already is a nickmerge-request for this player');
            $isValid = false;
        }
        
        if (!$this->playerTo = Doctrine::getTable('Player')->findOneBySimplified($to)) {
            
            $this->getElement('nick_to')->addError('Player was not found');
            $isValid = false;
        }
        
        return $isValid;
    }
}
