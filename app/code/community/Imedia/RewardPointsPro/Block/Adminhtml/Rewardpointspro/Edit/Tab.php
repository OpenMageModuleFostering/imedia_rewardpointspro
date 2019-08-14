<?php
/**
 *Reward Points Pro Row Edit Tab
*/
class Imedia_RewardPointsPro_Block_Adminhtml_Rewardpointspro_Edit_Tab extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('imedia_rewardpointspro')->__('Customer Reward Point'));
    }
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('imedia_rewardpointspro')->__('Customer Reward Point'),
            'title'     => Mage::helper('imedia_rewardpointspro')->__('Customer Reward Point'),
        ));

        return parent::_beforeToHtml();
    }
}