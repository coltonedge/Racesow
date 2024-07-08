<?php

/**
 * helpModel
 *
 * TODO: description
 */
class HelpOld
{
    /**
     * The FAQ table
     *
     * @var Zend_Db_Table_Abstract
     */
    private $_faqTable;
    
    /**
     * Id of the initially opened answer
     *
     * @var integer
     */
    private $_activeAnswer;

    /**
     * Get the help_faq-table
     *
     * @return Zend_Db_Table_Abstract
     */
    private function _getFAQtable()
    {
        if( null === $this->_faqTable )
        {
            $this->_faqTable = new FaqTableOld;
        }
        return $this->_faqTable;
    }

    /**
     * Set and answer to be shown initially
     *
     * @param integer $id
     * @return helpModel
     */
    public function showAnswer($id) {
        
        $this->_activeAnswer = (integer)$id;
        return $this;
    }
    
    /**
     * Get FAQ items
     *
     * @return array
     */
    public function getFaqItems()
    {
        $table = $this->_getFAQtable();
        $select = $table->select()->order("position ASC");
        $arr = $table->getAdapter()->fetchAll($select);

            foreach ($arr as &$item) {
            
                   $item->active = ($this->_activeAnswer == $item->id);
            }
 
            return $arr;
    }
}
