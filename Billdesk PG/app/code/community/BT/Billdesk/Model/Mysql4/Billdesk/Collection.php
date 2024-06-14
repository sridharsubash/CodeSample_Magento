<?php

class BT_Billdesk_Model_Mysql4_Billdesk_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('billdesk/billdesk');
    }
}