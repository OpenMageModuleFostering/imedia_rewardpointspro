<?php
/**
 * Reward points Observer
 * 
 */

class Imedia_RewardPointsPro_Model_Observer  extends  Mage_Core_Model_Abstract {
  	public function orderStatusChange($event)
    {
		$order       = $event->getOrder();
        $state       = $order->getState();
		$items       = $order->getAllVisibleItems();
		$cusId       = $order->getCustomerId();
		$rewardPoints = 0;
		$prodIds = array();
		if($state == 'canceled'){
			foreach ($items as $item) {
				$prodIds[] = $item->getProduct()->getId();
			}
			$prod = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('reward_points_pro')->addIdFilter($prodIds);
			//sum up points per product per quantity
			foreach ($items as $item) {
			$rewardPoints += $prod->getItemById($item->getProduct()->getId())->getRewardPointsPro() * $item->getQtyOrdered();
			}
			//delete customer points when order cancel
			$this->useCouponPoints($rewardPoints, $cusId);
		}
	}
	
	public function rewardpoints($observer){
		if(Mage::getStoreConfig('rewardpointspro/rewardpointspro/enabled')&& Mage::getStoreConfig('rewardpointspro/display/signup')){
	   
			$customer = $observer->getCustomer();
			$customerId = $customer->getEntityId();
			$rewardPoints = 0;
			$rewardPoints = Mage::getStoreConfig('rewardpointspro/display/signup_points');
			$this->recordPoints($rewardPoints, $customerId);
		}

    }
	public function subscribedToNewsletter(Varien_Event_Observer $observer){
	  
		if(Mage::getStoreConfig('rewardpointspro/rewardpointspro/enabled') && Mage::getStoreConfig('rewardpointspro/display/newsletter_signup')){ 
	        $event = $observer->getEvent();
			$subscriber = $event->getDataObject();
			$data = $subscriber->getData();
			$email = $data['subscriber_email'];
			$rewardPoints = 0;
			$customer = Mage::getModel('customer/customer');
			$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
			$customer->loadByEmail($email); //load customer by email id
			$customerid = $customer->getId();
            $subscribed = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
			if($subscribed->getId())
				{
				  $alreadysubscribed = $subscribed->getStatus();
				}
			else{$alreadysubscribed = 5;}	
				$statusChange = $subscriber->getIsStatusChanged();
			if($customerid && $alreadysubscribed==5){
			if ($data['subscriber_status'] == "1" && $statusChange == true) {
				$rewardPoints = Mage::getStoreConfig('rewardpoints/display/newsletter_signup_points');
				$this->recordPoints($rewardPoints, $customerid);
				 }
			  }
		}			
	}
	/**
	* Record the points for each product.
	*
	* @triggeredby: sales_order_place_after
	* @param $eventArgs array "order"=>$order
	*/
	public function recordPointsForOrderEvent($observer){
		if(Mage::getStoreConfig('rewardpointspro/rewardpointspro/enabled')&& Mage::getStoreConfig('rewardpointspro/display/product_rewards')){ 
			$order = $observer->getEvent()->getOrder();
			$items =$order->getItemsCollection();
			$customer_id_from_event = $order->getCustomerId();
			$customerId = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getCustomerId();
			$rewardPoints = 0;
			$prodIds = array();
			foreach ($items as $_item) {
				$prodIds[] = $_item->getProductId();
			}
			$prod = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('reward_points_pro')->addIdFilter($prodIds);
			//sum up points per product per quantity
			foreach ($items as $_item) {
			$rewardPoints += $prod->getItemById($_item->getProductId())->getRewardPointsPro() * $_item->getQtyOrdered();
			}
			//record points for item into db
			$this->recordPoints($rewardPoints, $customerId);
			$discounted = Mage::getSingleton('customer/session')->getApplyPoints();
			//subtract points for this order
			$this->useCouponPoints($discounted, $customerId); 
		   
		}
		Mage::getSingleton('customer/session')->setdisc(0);
	}
		
	
	public function recordPoints($pointsInt, $customerId){
		$points = Mage::getModel('rewardpointspro/account')->load($customerId);
		$points->addPoints($pointsInt, $customerId);
		$points->save();			
	}
	public function useCouponPoints($discounted, $customerId) {
		$pointsAmt = $discounted;
		$points = Mage::getModel('rewardpointspro/account')->load($customerId);
		$points->subtractPoints($pointsAmt, $customerId);
		$points->save();
	}
}