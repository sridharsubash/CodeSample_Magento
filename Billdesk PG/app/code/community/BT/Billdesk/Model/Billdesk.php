<?php

class BT_Billdesk_Model_Billdesk extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('billdesk/billdesk');
    }
}