<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Shopping cart controller
 */
require_once "Mage/Checkout/controllers/CartController.php";

class Imedia_RewardPointsPro_CartController extends Mage_Checkout_CartController
{
    public function applypointsAction(){
		$customerId = Mage::getSingleton('customer/session')->getCustomerId();
		$customerPoints = Mage::getModel('rewardpointspro/account')->load($customerId);
		$leftpoints = $customerPoints->getPointsCurrent();
		 
        if (!$this->_getCart()->getQuote()->getItemsCount()) {
            $this->_goBack();
            return;
        }

        $rewardCode = $this->getRequest()->getParam('reward_code_pro');
        $onePointValue = Mage::getStoreConfig('rewardpointspro/display/pointvalue');
		$discountAmount = '';
		$myrewards = $rewardCode*$onePointValue;
		if($onePointValue == ''){
			$discountAmount = $rewardCode;
		}else{
			$discountAmount = $myrewards;
		}
		
		if ($this->getRequest()->getParam('removepoints') == 1) {
            $discountAmount = '';
			$rewardCode = '';
			Mage::getSingleton('customer/session')->setdisc($discountAmount);
			Mage::getSingleton('customer/session')->setApplyPoints($rewardCode);
		    $this->_getSession()->addSuccess($this->__('Reward points was canceled.'));
			$this->_goBack();
        }else{
            $codeLength1 = strlen($rewardCode);
			$validtotal = Mage::helper('checkout/cart')->getQuote()->getSubtotal();
            if ($codeLength1 && $rewardCode <=$leftpoints && $rewardCode > 0 && $rewardCode <= $validtotal) {
			        Mage::getSingleton('customer/session')->setdisc($discountAmount);
					Mage::getSingleton('customer/session')->setApplyPoints($rewardCode);
                    $this->_getSession()->addSuccess(
                        $this->__('Reward Points "%s" was applied.', Mage::helper('core')->escapeHtml($rewardCode))
                    );
			}else{
				if ($leftpoints==0 or ($leftpoints < $rewardCode and $leftpoints <= $validtotal)) {
						$this->_getSession()->addError(
							$this->__('You do not have enough points to redeem.', Mage::helper('core')->escapeHtml($rewardCode))
						); 
					}	
				 else{  
					  $this->_getSession()->addError(
							$this->__('reward Points "%s" is not valid.', Mage::helper('core')->escapeHtml($rewardCode))
						);  
					}
                } 
		}

        $this->_goBack();
    }

	
	
	
}
