<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

require_once Mage::getModuleDir('controllers', 'Grafikentwicklung_Tracking') . DS . 'Checkout' . DS . 'OnepageAbstractController.php';

/**
 * Class Grafikentwicklung_Tracking_OnepageController
 */
class Grafikentwicklung_Tracking_Checkout_OnepageController extends Grafikentwicklung_Tracking_Checkout_OnepageAbstractController
{

    /**
     * @return Grafikentwicklung_Tracking_Helper_Data
     */
    protected function getTrackingHelper()
    {
        return Mage::helper('grafikentwicklung_tracking');
    }


    /**
     * Order success action
     */
    public function successAction()
    {
        /** @var bool $isSuccessDebugEnabled */
        $isSuccessDebugEnabled = $this->getTrackingHelper()->isCheckoutSuccessDebugEnabled();

        /** @var Mage_Checkout_Model_Session $session */
        $session = $this->getOnepage()->getCheckout();

        if (!$isSuccessDebugEnabled) {
            if (!$session->getLastSuccessQuoteId()) {
                $this->_redirect('checkout/cart');
                return;
            }
        }

        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();

        if (!$isSuccessDebugEnabled) {
            $lastRecurringProfiles = $session->getLastRecurringProfileIds();
            if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
                $this->_redirect('checkout/cart');
                return;
            }

            $session->clear();
        }

        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
        $this->renderLayout();
    }
}

