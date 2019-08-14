<?php
class Imedia_RewardPointsPro_Model_Account extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('imedia_rewardpointspro/account');
    }
	
   protected $customerId = -1;
   protected $storeId    = -1;
   protected $pointsCurrent  = NULL;
   protected $pointsReceived = NULL;
   protected $pointsSpent    = NULL;
   
   
	public function save(){
				$connection = Mage::getSingleton('core/resource')->getConnection('rewardpointspro_write');
								
				$connection->beginTransaction();
				$fields = array();
				$fields['customer_id'] = $this->customerId;
				$fields['store_id'] = $this->storeId;
				$fields['points_current'] = $this->pointsCurrent;
				$fields['points_received'] = $this->pointsReceived;

				
				try {
						$this->_beforeSave();
						if (!is_null($this->rewardpointsAccountId)) {
						  
						  
							$where = $connection->quoteInto('customer_id=?',$fields['customer_id']);
							$connection->update('rewardpointspro_account',	$fields, $where);
				        } 
				        else {
							$connection->insert('rewardpointspro_account', $fields);
							//$this->rewardpointsAccountId =$connection->lastInsertId('rewardpoints_account');
							//$this->rewardpointsAccountId =2;
				        }
					$connection->commit();
					$this->_afterSave();
					}
				catch (Exception $e) {
						$connection->rollBack();
						throw $e;
				}
					return $this;
	}
	public function updatepoints($p, $customerId) {
			$connection = Mage::getSingleton('core/resource')->getConnection('rewardpointspro_write');
			$connection->beginTransaction();
			$fields = array('points_current'=>$p);
			$where = $connection->quoteInto('customer_id=?',$customerId);
			$connection->update('rewardpointspro_account',	$fields, $where);
	}			
	public function load($id , $field=null) {
			if ($field === null) {
			$field = 'customer_id';
			}
			$connection = Mage::getSingleton('core/resource')->getConnection('rewardpointspro_read');
			$select = $connection->select()->from('rewardpointspro_account')->where('rewardpointspro_account.'.$field.'=?', $id);
			$data = $connection->fetchRow($select);
			if (!$data) {
			return $this;
			}
			$this->setRewardpointsAccountId($data['rewardpoints_account_id']);
			$this->setCustomerId($data['customer_id']);
			$this->setStoreId($data['store_id']);
			$this->setPointsCurrent($data['points_current']);
			$this->setPointsReceived($data['points_received']);
			$this->setPointsSpent($data['points_spent']);
			$this->_afterLoad();
			return $this;
	}
		
		
	public function addPoints($p, $customerId) {
		  $collpoint = Mage::getModel('rewardpointspro/account')->load($customerId);
		  if($collpoint){
			  $pointsCurrent = $collpoint->getPointsCurrent();
			  $pointsReceived = $collpoint->getPointsReceived();
			  $pointsSpent = $collpoint->getPointsSpent();
		  }
		  else{$pointsSpent = 0;}
	      $storeId    = Mage::app()->getStore()->getStoreId();;
		  Mage::log('add points $p: '. $p);
		  $this->pointsCurrent = $pointsCurrent + $p;
		  $this->pointsReceived = $pointsReceived + $p;
		  $this->pointsSpent = $pointsSpent;
		  $this->customerId = $customerId;
		  $this->storeId = $storeId;
	}
		
	public function subtractPoints($p, $customerId) {
		  $collpoint = Mage::getModel('rewardpointspro/account')->load($customerId);
		  if($collpoint){
			  $pointsCurrent = $collpoint->getPointsCurrent();
			  $pointsReceived = $collpoint->getPointsReceived();
			  $pointsSpent = $collpoint->getPointsSpent();
		  }
		  else{$pointsSpent = 0;}
		  $storeId    = Mage::app()->getStore()->getStoreId();;
		  Mage::log('add points $p: '. $p);
		  $this->pointsCurrent = $pointsCurrent - $p;
		  $this->pointsSpent = $pointsSpent + $p;
		  $this->customerId = $customerId;
		  $this->pointsReceived = $pointsReceived;
		  $this->storeId = $storeId;
	}
					
	
}