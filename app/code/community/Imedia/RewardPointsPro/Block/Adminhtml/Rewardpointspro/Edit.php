<?php
/**
 * Reward Points Pro grid row edit block
 * 
*/
class Imedia_RewardPointsPro_Block_Adminhtml_Rewardpointspro_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		$this->_blockGroup = 'imedia_rewardpointspro';
		$this->_controller = 'adminhtml_rewardpointspro';
		parent::__construct();
	}

	/**
	 * Get Header text
	 *
	 * @return string
	*/
	public function getHeaderText()
	{
		return Mage::helper('imedia_rewardpointspro')->__('Customer Reward Points');
		
	}
}