<?php
/**
 *Reward Points Widget Form
*/
class Imedia_RewardPointsPro_Block_Adminhtml_Rewardpointspro_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('imedia_rewardpointspro_rewardpointspro_edit_form');
        $this->setTitle(Mage::helper('imedia_rewardpointspro')->__('Reward Point Information'));
    }

    /**
     * Setup form fields for inserts/updates
     *
    */
    protected function _prepareForm()
    {
        $cusId         = $this->getRequest()->getParam('id');
		$customer_data = Mage::getModel('customer/customer')->load($cusId);
		$rewardPoints  = Mage::getModel('imedia_rewardpointspro/account')->load($cusId);
		
		$form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action'  => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'  => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('imedia_rewardpointspro')->__('Reward Point Information'),
            'class' => 'fieldset-wide',
        ));
		$fieldset->addField('customer_firstname', 'label', array(
            'label' => Mage::helper('imedia_rewardpointspro')->__('Customer FirstName'),
			'value' => $customer_data->getFirstname()
        ));
		$fieldset->addField('customer_lastname', 'label', array(
            'label' => Mage::helper('imedia_rewardpointspro')->__('Customer LastName'),
			'value' => $customer_data->getLastname()
        ));
		$fieldset->addField('customer_email', 'label', array(
            'label' => Mage::helper('imedia_rewardpointspro')->__('Customer Email'),
			'value' => $customer_data->getEmail()
        ));
		
		$fieldset->addField('reward_point', 'text', array(
            'label' => Mage::helper('imedia_rewardpointspro')->__('Current Reward Points'),
			'value' => $rewardPoints->getPointsCurrent(),
			'name'  => 'reward_points'
        ));
		
		$form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}