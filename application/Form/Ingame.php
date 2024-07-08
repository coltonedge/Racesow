<?php

/**
 * Form for changing ingame linkage
 *
 */
class Form_Ingame extends Racenet_Form
{
    /**
     * Constructor replacement
     *
     */
    public function init()
    {

        //ingame nickname
        $ingame = $this->createElement('text', 'simplified');
        $ingame->setLabel('ingame Nickname')
               ->setRequired(true);
        $this->addElement($ingame);

        //submit
        $elem = $this->createElement('submit', 'save');
        $elem->setLabel('Link')
                 ->setIgnore(true);
        $this->addButtonElement($elem);
    }
    
    public function isValid($data)
    {
        $isValid = parent::isValid($data);
        
        // trying to link to "player" ?
        if (strtolower(trim($data['simplified'])) == 'player') {
            
            $this->addErrorMessage('You can\'t be linked to "player", please choose another nickname.');
            $isValid = false;
        }
        
        if (empty($data['simplified']) ||!$player = Doctrine::getTable('Player')->findOneBySimplified($data['simplified'])) {
                    
            $this->addErrorMessage('This nickname was not found in the database.');
            $isValid = false;
        }
        
        else if ($linkage = Doctrine::getTable('PlayerPhpbbuser')->findOneByPlayerId((integer)$player->id)) {
            
            if ($linkage->user_id != RacenetAccount::getInstance()->user_id) {
            
                $this->addErrorMessage('This nickname is already linked to another account.');
                $isValid = false;
            }
        }
        
        return $isValid;
    }
}
