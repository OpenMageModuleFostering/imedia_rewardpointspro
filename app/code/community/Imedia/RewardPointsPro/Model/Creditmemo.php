<?php
class Imedia_RewardPointsPro_Model_Creditmemo extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract {
 
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo) {
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $creditmemo->getFeeAmountPro());
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $creditmemo->getBaseFeeAmountPro());
		$creditmemo->setFeeAmountPro($creditmemo->getFeeAmountPro());
		$creditmemo->setBaseFeeAmountPro($creditmemo->getBaseFeeAmountPro());

        return $this;
    }
 
}