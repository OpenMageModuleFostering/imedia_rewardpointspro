<?php
/**
 * Reward Points Pro grid block
 * 
*/
class Imedia_RewardPointsPro_Block_Adminhtml_Rewardpointspro_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('rewardpointsproGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    
    /**
     * Prepare rewardpointspro grid collection object
     *
     * @return Imedia_RewardPointsPro_Block_Adminhtml_Rewardpointspro_Grid
     */
    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        $collection = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('firstname')
			->addAttributeToSelect('lastname')
            ->addAttributeToSelect('entity_id');          

		$collection->joinField('points_current',
			'rewardpointspro_account',
			'points_current',
			'customer_id=entity_id'
			);


        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }
    
    /**
     * Prepare default grid column
     *
     * @return Imedia_RewardPointsPro_Block_Adminhtml_Rewardpointspro_Grid
     */
    protected function _prepareColumns()
    {
	
	    $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('imedia_rewardpointspro')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
        ));
		      
        $this->addColumn('firstname',
            array(
                'header'=> Mage::helper('imedia_rewardpointspro')->__('Customer FirstName'),
                'index' => 'firstname',
        ));

		$this->addColumn('lastname',
            array(
                'header'=> Mage::helper('imedia_rewardpointspro')->__('Customer LastName'),
                'index' => 'lastname',
        ));

        $this->addColumn('email',
                array(
                    'header'=> Mage::helper('imedia_rewardpointspro')->__('Customer Email'),
                    'index' => 'email',
            )); 
		
		$this->addColumn('points_current',
                array(
                    'header'=> Mage::helper('imedia_rewardpointspro')->__('Customer Rewards Points'),
                    'index' => 'points_current',
		));
		$this->addExportType('*/*/exportCsv',Mage::helper('imedia_rewardpointspro')->__('CSV'));
		
		return parent::_prepareColumns();
    }

	public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}

