<?php
class Imedia_RewardPointsPro_Model_Discount extends Mage_Sales_Model_Quote_Address_Total_Abstract {
 
     protected $_code = 'fee';
 
    public function collect(Mage_Sales_Model_Quote_Address $address) {
			parent::collect($address);

			$this->_setAmount(0);
			$this->_setBaseAmount(0);

			$items = $this->_getAddressItems($address);
			if (!count($items)) {
				return $this; //this makes only address type shipping to come through
			}

			$quote = $address->getQuote();
	
            if ($address->getData('address_type') == 'billing')
				
				
				$exist_amount = $quote->getFeeAmountPro();
				$fee = Mage::getSingleton('customer/session')->getDisc(); //your discount
				$discount = $fee - $exist_amount ;
 
                $address->setFeeAmountPro($discount);
                $address->setBaseFeeAmountPro($discount);
				
				$grandTotal = $address->getGrandTotal();
                $baseGrandTotal = $address->getBaseGrandTotal();
				$quote->setFeeAmountPro($discount);
         				 
                $address->setGrandTotal($grandTotal - $address->getFeeAmountPro());
                $address->setBaseGrandTotal($baseGrandTotal - $address->getBaseFeeAmountPro());				
                
        return $this;
    }
	
	public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
	    $rewards1 = $address->getFeeAmountPro();
        if ($rewards1!=0) {
            $title = Mage::helper('sales')->__('Reward Points Discount');
            $code = $address->getCouponCode();
            if (strlen($code)) {
                $title = Mage::helper('sales')->__('Reward Points Discount (%s)', $code);
            }
            $address->addTotal(array(
                'code'=>$this->getCode(),
                'title'=>$title,
                'value'=>$rewards1
            ));
			
        }
        return $this;
    }
 
}