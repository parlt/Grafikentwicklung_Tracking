<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Helper_Data
 */
class Grafikentwicklung_Tracking_Helper_Data extends Grafikentwicklung_Tracking_Helper_Config_Data
{

    /**
     * @return Grafikentwicklung_Tracking_Helper_Session_Data
     */
    protected function getSessionHelper()
    {
        return Mage::helper('grafikentwicklung_tracking_session');
    }


    /**
     * @return Grafikentwicklung_Tracking_Helper_Config_Data
     */
    protected function getConfigHelper()
    {
        return Mage::helper('grafikentwicklung_tracking_config');
    }


    /**
     * @return Mage_Adminhtml_Model_Session
     */
    private function getAdminSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }


    /**
     * @return Grafikentwicklung_Tracking_Model_Emptycarttracking
     */
    private function getEmptyCartTrackingModel()
    {
        /** @var Grafikentwicklung_Tracking_Model_Emptycarttracking $model */
        $model = Mage::getModel('grafikentwicklung_tracking/emptycarttracking');
        $model->loadBySessionAndStoreData(
            $this->getSessionHelper()->getCustomerSessionId(),
            $this->getSessionHelper()->getStoreCode(),
            $this->getSessionHelper()->getRemoteAddress(),
            $this->getSessionHelper()->getHttpUserAgent()
        );
        return $model;
    }


    /**
     * @return Grafikentwicklung_Tracking_Model_Salestracking
     */
    private function getSalesTrackingModel()
    {
        /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
        $model = Mage::getModel('grafikentwicklung_tracking/salestracking');
        $model->loadBySessionId($this->getSessionHelper()->getCustomerSessionId());
        return $model;
    }


    /**
     * @return bool
     */
    private function hasSessionId()
    {
        return !empty(session_id());
    }


    /**
     * @return Grafikentwicklung_Tracking_Model_Emptycarttracking
     */
    public function getEmptyCartModelWithSessionData()
    {
        /** @var Grafikentwicklung_Tracking_Model_Emptycarttracking $model */
        $model = $this->getEmptyCartTrackingModel();
        if (empty($model->getData())) {
            $model->setData($this->getSessionHelper()->getSessionDataAsArray());
        }
        return $model;
    }


    /**
     * @return Grafikentwicklung_Tracking_Model_Salestracking
     */
    public function getSalesTrackingModelBySessionId()
    {
        /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
        $model = Mage::getModel('grafikentwicklung_tracking/salestracking');
        $model->loadBySessionId($this->getSessionHelper()->getCustomerSessionId());
        return $model;
    }


    /**
     * @param string $url
     * @return $this
     */
    public function storeRequestedUrl($url)
    {
        if ($this->hasSessionId()) {

            /** @var Grafikentwicklung_Tracking_Model_Emptycarttracking $model */
            $model = $this->getEmptyCartModelWithSessionData();
            $model->storeRequestedUrl($url);
        }

        return $this;
    }


    /**
     * @param string $url
     * @return $this
     */
    public function storeShoppingPortalReferrer($url)
    {
        if ($this->hasSessionId()) {

            /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
            $model = $this->getSalesTrackingModelBySessionId();
            $model->storePortal($url);
        }

        return $this;
    }


    /**
     * @return bool
     */
    public function isCheckoutSuccessPage()
    {
        if (Mage::app()->getFrontController()->getAction()->getFullActionName() == 'checkout_onepage_success') {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function isCurrentDomainExcluded()
    {
        if ($this->isExcludeDomainsEnabled()) {
            /** @var string $currentDomain */
            $currentDomain = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);

            /** @var string[] $excludedDomains */
            $excludedDomains = $this->getExcludedDomains();

            /** @var string $excludedDomain */
            foreach ($excludedDomains as $excludedDomain){
                if (strpos($currentDomain, $excludedDomain) !== false){
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * @return bool|unknown
     */
    private function isAdminArea()
    {
        return Mage::app()->getStore()->isAdmin();
    }


    /**
     * @return bool
     */
    public function canUseTagManager()
    {
        if ($this->isAdminArea()) {
            return false;
        }

        if (!$this->isTagManagerEnabled()) {
            return false;
        }
        if (!$this->getTagMangerContainerId()) {
            return false;
        }

        if ($this->isCurrentDomainExcluded()) {
            return false;
        }

        return true;
    }


    /**
     * @return bool
     */
    public function canUseTagManagerOnCheckoutSuccessPage()
    {
        if (!$this->canUseTagManager()) {
            return false;
        }

        if (!$this->getTagManagerOrderCompletedEventName()) {
            return false;
        }

        if (!$this->isCheckoutSuccessPage()) {
            return false;
        }

        return true;
    }


    /**
     * @return bool
     */
    public function canUseMoebelDeTracking()
    {
        if ($this->isAdminArea()) {
            return false;
        }

        if ($this->isCurrentDomainExcluded()) {
            return false;
        }

        if (!$this->isCheckoutSuccessPage()) {
            return false;
        }

        if (!$this->isMoebelDeEnabled()) {
            return false;
        }

        if (!$this->getMoebelDeTrackingKey()) {
            return false;
        }

        return true;
    }


    /**
     * @return bool
     */
    public function canUseShopZillaTracking()
    {
        if ($this->isAdminArea()) {
            return false;
        }

        if ($this->isCurrentDomainExcluded()) {
            return false;
        }

        if (!$this->isCheckoutSuccessPage()) {
            return false;
        }

        if (!$this->isShopZillaEnabled()) {
            return false;
        }

        if (!$this->getShopZillaMerchantId()) {
            return false;
        }

        return true;
    }


    /**
     * @return bool
     */
    public function canSendEmptyCartMail()
    {
        if ($this->isEmptyCartTrackingEnabled()
            && $this->isEmptyCartTrackingSendMailEnabled()
            && $this->getEmptyCartTrackingMailTemplateIdentity()
            && $this->getEmptyCartTrackingSendMailFromMailAddress()
            && $this->getEmptyCartTrackingSendMailSubject()
            && $this->getEmptyCartTrackingSendMailToMailAddress()
        ) {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function canSendSalesTrackingMail()
    {
        if ($this->isSalesTrackingEnabled()
            && $this->isSalesTrackingSendMailEnabled()
            && $this->getSalesTrackingMailTemplateIdentity()
            && $this->getSalesTrackingSendMailFromMailAddress()
            && $this->getSalesTrackingSendMailSubject()
            && $this->getSalesTrackingSendMailToMailAddress()
        ) {
            return true;
        }

        return false;
    }

}