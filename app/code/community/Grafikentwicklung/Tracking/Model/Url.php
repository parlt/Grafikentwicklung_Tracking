<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Model_Url
 */
class Grafikentwicklung_Tracking_Model_Url extends Mage_Core_Model_Url
{

    /**
     * @return Grafikentwicklung_Tracking_Helper_Data
     */
    protected function getTrackingHelper()
    {
        return Mage::helper('grafikentwicklung_tracking');
    }


    /**
     * @param   string $url
     * @return  Mage_Core_Model_Url
     */
    public function parseUrl($url)
    {
        if ($this->getTrackingHelper()->isEmptyCartTrackingEnabled()) {
            $this->getTrackingHelper()->storeRequestedUrl($url);
        }

        if ($this->getTrackingHelper()->isSalesTrackingEnabled()) {
            $this->getTrackingHelper()->storeShoppingPortalReferrer($url);
        }

        parent::parseUrl($url);
        return $this;
    }

}
