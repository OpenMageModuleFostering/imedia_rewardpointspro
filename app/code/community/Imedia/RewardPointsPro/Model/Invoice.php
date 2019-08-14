<?php
class Imedia_RewardPointsPro_Model_Invoice extends Mage_Sales_Model_Order_Invoice_Total_Abstract {
 
    public function collect(Mage_Sales_Model_Order_Invoice $invoice) {
                         
                $invoice->setGrandTotal($invoice->getGrandTotal() - $invoice->getFeeAmountPro());
                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $invoice->getBaseFeeAmountPro());
				
				$invoice->setFeeAmountPro($invoice->getFeeAmountPro());
				$invoice->setBaseFeeAmountPro($invoice->getBaseFeeAmountPro()); 
				
        return $this;
		
    }
 
}
