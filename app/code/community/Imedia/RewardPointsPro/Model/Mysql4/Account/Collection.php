<?php
class Imedia_RewardPointsPro_Model_Mysql4_Account_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('imedia_rewardpointspro/account');
    }
}