<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        25.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Model_Observer
 */
class Grafikentwicklung_Tracking_Model_Observer
{
    /**
     * @return Mage_Checkout_Model_Cart|Mage_Core_Model_Abstract
     */
    protected function getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }


    /**
     * @return Grafikentwicklung_Tracking_Helper_Data
     */
    protected function getTrackingHelper()
    {
        return Mage::helper('grafikentwicklung_tracking');
    }


    /**
     * @param Varien_Event_Observer $observer
     */
    public function logEmptyCart(Varien_Event_Observer $observer)
    {
        if ($this->getTrackingHelper()->isEmptyCartTrackingEnabled()) {

            $cart = $this->getCart();
            /** @var Grafikentwicklung_Tracking_Model_Emptycarttracking $model */
            $model = $this->getTrackingHelper()->getEmptyCartModelWithSessionData();
            if (!$cart->getQuote()->getItemsCount()) {
                $model->logEmptyCart();
            } else {
                $model->delete();
            }
        }
    }


    /**
     * @param Varien_Event_Observer $observer
     */
    public function removeEmptyCartLog(Varien_Event_Observer $observer)
    {
        if ($this->getTrackingHelper()->isEmptyCartTrackingEnabled()) {
            $model = $this->getTrackingHelper()->getEmptyCartModelWithSessionData();
            $model->delete();
        }
    }


    /**
     * @param Varien_Event_Observer $observer
     */
    public function logSalesTrackingTransactionData(Varien_Event_Observer $observer)
    {
        if ($this->getTrackingHelper()->isSalesTrackingEnabled()) {

            /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
            $model = $this->getTrackingHelper()->getSalesTrackingModelBySessionId();
            $model->logTransactionData();
        }
    }
}