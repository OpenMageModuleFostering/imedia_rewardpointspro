<?php
/**
 * Admin manage Reward pointspro controller
 */
class Imedia_RewardPointsPro_Adminhtml_RewardpointsproController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init actions
     *
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->_title($this->__('Reward Points'));
        
        $this->loadLayout()
            ->_setActiveMenu('catalog/reward_pointspro')
            ->_addBreadcrumb(Mage::helper('imedia_rewardpointspro')->__('Reward Points')
                    , Mage::helper('imedia_rewardpointspro')->__('Reward Points'));
        return $this;
    }
    
    /**
     * Index action method
     */
    public function indexAction() 
    {
        $this->_initAction();
        $this->renderLayout();
    }
    
    /**
     * Used for Ajax Based Grid
     *
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('imedia_rewardpointspro/adminhtml_rewardpointspro_grid')->toHtml()
        );
    }
	public function exportCsvAction()
	{
		$fileName   = 'rewardpoints.csv';
		$content    = $this->getLayout()->createBlock('imedia_rewardpointspro/adminhtml_rewardpointspro_grid')->getCsvFile();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	public function editAction()
    {
        $this->_initAction()
             ->_addContent($this->getLayout()->createBlock('imedia_rewardpointspro/adminhtml_rewardpointspro_edit')->setData('action', $this->getUrl('*/*/save')))
             ->_addLeft($this->getLayout()->createBlock('imedia_rewardpointspro/adminhtml_rewardpointspro_edit_tab'))
             ->renderLayout();
    }
	public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()){
            $cusId     = $this->getRequest()->getParam('id');
			$pointsInt = $postData['reward_points']; 
			$points    = Mage::getModel('rewardpointspro/account')->load($cusId);
			$postValues = array('points_current' => $pointsInt);
			$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
 			$connection->beginTransaction();
			$where = $connection->quoteInto('customer_id =?', $cusId);
			
			try {
				
				$connection->update('rewardpointspro_account', $postValues, $where);
				$connection->commit();
				
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('imedia_rewardpointspro')->__('The Customer Points has been saved.'));
                $this->_redirect('*/*/');

                return;
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('imedia_rewardpointspro')->__('An error occurred while saving this status.'));
            }
            $this->_redirectReferer();
        }
    }
	
  
}