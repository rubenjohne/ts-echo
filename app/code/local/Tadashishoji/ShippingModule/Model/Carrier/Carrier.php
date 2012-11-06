<?php
/**
 * unique identifier for our shipping module
 * @var string $_code
 * @author ruben
 */
class TadashiShoji_ShippingModule_Model_Carrier_Carrier extends Mage_Shipping_Model_Carrier_Abstract {

    /**
    * unique identifier for our shipping module
    * @var string $_code
    */
    protected $_code = 'shippingmodule';


    /**
    * Collect rates for this shipping method based on information
    in $request
    *
    * @param Mage_Shipping_Model_Rate_Request $data
    * @return Mage_Shipping_Model_Rate_Result
    */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigData('active')) {
            Mage::log('The '.$this->_code.' shipping method is not active.');
            return false;
        }

        $handling = $this->getConfigData('handling_fee');
        $result = Mage::getModel('shipping/rate_result');

        foreach ($request as $method) {
            $method = Mage::getModel('shipping/rate_result_method');

            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));

            $method->setMethod($method['code']);
            $method->setMethodTitle($method['title']);

            $method->setCost($method['amount']);

            $method->setPrice($method['amount'] + $handling);

            $result->append($method);
        }

        return $result;
    }
}
