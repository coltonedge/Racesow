<?php

/**
 * Form for asking a question
 *
 */
class Form_Ask extends Racenet_Form
{
    /**
     * Constructor replacement
     *
     */
    public function init()
    {
        // question
        $elem = $this->createElement('textarea', 'question');
        $elem->setLabel('Question');
        $this->addElement($elem);
        
        // submit button
        $elem = $this->createElement('submit', 'save');
        $elem->setLabel('Ask Question')
                 ->setIgnore(true);
        $this->addButtonElement($elem);
    }
}
