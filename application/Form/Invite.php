<?php

/**
 * Form for requesting nickname merges
 *
 */
class Form_Invite extends Racenet_Form
{
    /**
     * Constructor replacement
     *
     */
    public function init()
    {
		// player name
        $elem = $this->createElement('text', 'name');
        $elem->setLabel('Name')
           ->setDescription('The name of player to intive')
		   ->setRequired(true);
        $this->addElement($elem);
		
        // submit button
        $elem = $this->createElement('submit', 'save');
        $elem->setLabel('Invite player')
             ->setIgnore(true);
        $this->addButtonElement($elem);
    }
	
	/**
	 * Validate the form
	 */
	public function isValid($data)
	{
		$isValid = parent::isValid($data);
	
		return $isValid;
	}
}
