<?php
class Imedia_RewardPointsPro_Model_Mysql4_Account extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('imedia_rewardpointspro/account', 'id');
    }
}