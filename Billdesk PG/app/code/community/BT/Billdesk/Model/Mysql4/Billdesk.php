<?php

class BT_Billdesk_Model_Mysql4_Billdesk extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the billdesk_id refers to the key field in your database table.
        $this->_init('billdesk/billdesk', 'billdesk_id');
    }
}