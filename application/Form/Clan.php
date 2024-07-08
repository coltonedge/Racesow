<?php

/**
 * Form for requesting nickname merges
 *
 */
class Form_Clan extends Racenet_Form
{
    /**
     * Constructor replacement
     *
     */
    public function init()
    {
		// clan name
        $elem = $this->createElement('text', 'name');
        $elem->setLabel('Name')
           ->setDescription('The name of the clan or group')
		   ->setRequired(true);
        $this->addElement($elem);
    
		$elem = $this->createElement('select', 'type');
		$elem->setLabel('Group Type')
			->setMultioptions(array(
				'' => 'select group type...',
				'private' => 'Private Group (Clan)',
				'public' => 'Public group (Fun)'))
			->setRequired(true);
		$this->addElement($elem);
	
        // clan description
        $elem = $this->createElement('text', 'description');
        $elem->setLabel('Description');
        $this->addElement($elem);
        
        // website
        $elem = $this->createElement('text', 'website');
        $elem->setLabel('Website');
        $this->addElement($elem);
        
        // clantag
        $elem = $this->createElement('text', 'tag');
        $elem->setLabel('Clan tag');
        $this->addElement($elem);
		
        // submit button
        $elem = $this->createElement('submit', 'save');
        $elem->setLabel('Save group')
             ->setIgnore(true);
        $this->addButtonElement($elem);

    }
}
