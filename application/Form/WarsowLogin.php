<?php

/**
 * Form for asking a question
 *
 */
class Form_WarsowLogin extends Racenet_Form
{
    /**
     * Constructor replacement
     *
     */
    public function init()
    {
        // name
        $elem = $this->createElement('text', 'name')
            ->setLabel('Username')
            ->setRequired(true);
        
        $this->addElement($elem);
        
        // pass
        $elem = $this->createElement('password', 'pass')
            ->setLabel('Password')
            ->setRequired(true);
            
        $this->addElement($elem);
        
        // submit button
        $elem = $this->createElement('submit', 'save')
            ->setLabel('Login')
            ->setIgnore(true);
        $this->addButtonElement($elem);
    }
}
