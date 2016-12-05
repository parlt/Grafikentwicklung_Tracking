<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Helper_Config_Data
 */
class Grafikentwicklung_Tracking_Helper_Config_Data extends Mage_Core_Helper_Abstract
{

    /**
     * @return bool
     */
    public function isExcludeDomainsEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/excludes/enabled');
        return (bool)$value;
    }


    /**
     * @return array
     */
    public function getExcludedDomains()
    {
        $domains = [];
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/excludes/domains_list');

        if (!empty($value)) {
            $domains = explode(',', str_replace(["\n", "\r"], '', $value));
        }
        return $domains;
    }


    /**
     * @return bool
     */
    public function isCheckoutSuccessDebugEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/debug/checkout_success_debug_enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    protected function isTagManagerEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getTagMangerContainerId()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/container_id');

        return $value;
    }


    /**
     * @return string|null
     */
    public function getTagManagerOrderCompletedEventName()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/order_completed_event_name');
        return $value;
    }


    /**
     * @return bool
     */
    protected function isTagManagerDynamicRemarketingEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/dynamic_remarketing_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getTagManagerDynamicRemarketingEventName()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/dynamic_remarketing_event_name');
        return $value;
    }


    /**
     * @return bool
     */
    protected function isTagManagerDynamicRemarketingEnabledOnStartPage()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/dynamic_remarketing_on_startpage_enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    protected function isTagManagerDynamicRemarketingEnabledOnCategoriePages()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/dynamic_remarketing_on_categorie_pages_enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    protected function isTagManagerDynamicRemarketingEnabledOnProductDetailPage()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/dynamic_remarketing_on_product_detail_page_enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    protected function isTagManagerDynamicRemarketingEnabledOnCmsPages()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/dynamic_remarketing_on_cms_pages_enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    protected function isTagManagerDynamicRemarketingEnabledOnSearchPages()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/dynamic_remarketing_on_search_pages_enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    protected function isTagManagerDynamicRemarketingEnabledOnCartPage()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/dynamic_remarketing_on_cart_page_enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    protected function isTagManagerDynamicRemarketingEnabledOnOrderSuccessPage()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/tagmanger/dynamic_remarketing_on_order_success_enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    protected function isMoebelDeEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/moebel_de/enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getMoebelDeTrackingKey()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/moebel_de/tracking_key');
        return $value;
    }


    /**
     * @return bool
     */
    protected function isShopZillaEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/shopzilla/enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getShopZillaMerchantId()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/shopzilla/merchant_id');
        return $value;
    }


    /**
     * @return bool
     */
    public function isSalesTrackingEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    public function isSalesTrackingWriteLogFileEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/log_file_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingLogFileName()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/log_file_name');
        return $value;
    }


    /**
     * @return bool
     */
    public function isSalesTrackingSendMailEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/send_mail_enabled');
        return (bool)$value;
    }


    /**
     * @return string
     */
    public function getSalesTrackingMailTemplateIdentity()
    {
        return 'grafikentwicklung_tracking_sales_tracking_notification';
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingSendMailFromMailAddress()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/mail_from_mailaddress');
        if (empty($value)) {
            return null;
        }

        $value = Mage::getStoreConfig('trans_email/ident_' . $value . '/email');
        return $value;
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingSendMailSubject()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/mail_subject');
        return $value;
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingSendMailToMailAddress()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/mail_to_mailaddress');
        if (empty($value)) {
            return null;
        }

        $value = Mage::getStoreConfig('trans_email/ident_' . $value . '/email');
        return $value;
    }


//    /**
//     * @return string|null
//     */
//    public function getSalesTrackingSendMailCCMailAddress()
//    {
//        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/mail_cc_mailaddress');
//        return $value;
//    }


    /**
     * @return bool
     */
    public function isSalesTrackingBilligerDeEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/billiger_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingBilligerDeReferrer()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/billiger_referer');
        return $value;
    }


    /**
     * @return bool
     */
    public function isSalesTrackingGoogleEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/google_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingGoogleReferrer()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/google_referer');
        return $value;
    }


    /**
     * @return bool
     */
    public function isSalesTrackingMoebelDeEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/moebel_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingMoebelDeReferrer()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/moebel_enabled');
        return $value;
    }


    /**
     * @return bool
     */
    public function isSalesTrackingPreisroboterEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/preisroboter_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingPreisroboterReferrer()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/preisroboter_referer');
        return $value;
    }


    /**
     * @return bool
     */
    public function isSalesTrackingShopwahlEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/shopwahl_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingShopwahlReferrer()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/shopwahl_referer');
        return $value;
    }


    /**
     * @return bool
     */
    public function isSalesTrackingShopZillaEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/shopzilla_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getSalesTrackingShopZillaReferrer()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/sales_tracking/shopzilla_referer');
        return $value;
    }


    /**
     * @return bool
     */
    public function isEmptyCartTrackingEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/emty_cart_tracking/enabled');
        return (bool)$value;
    }


    /**
     * @return bool
     */
    public function isEmptyCartTrackingWriteLogFileEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/emty_cart_tracking/log_file_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getEmptyCartTrackingLogFileName()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/emty_cart_tracking/log_file_name');
        return $value;
    }


    /**
     * @return bool
     */
    public function isEmptyCartTrackingSendMailEnabled()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/emty_cart_tracking/send_mail_enabled');
        return (bool)$value;
    }


    /**
     * @return string|null
     */
    public function getEmptyCartTrackingMailTemplateIdentity()
    {
        return 'grafikentwicklung_tracking_empty_cart_notification';
    }


    /**
     * @return string|null
     */
    public function getEmptyCartTrackingSendMailFromMailAddress()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/emty_cart_tracking/mail_from_mailaddress');
        if (empty($value)) {
            return null;
        }

        $value = Mage::getStoreConfig('trans_email/ident_' . $value . '/email');
        return $value;
    }


    /**
     * @return string|null
     */
    public function getEmptyCartTrackingSendMailSubject()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/emty_cart_tracking/mail_subject');
        return $value;
    }


    /**
     * @return string|null
     */
    public function getEmptyCartTrackingSendMailToMailAddress()
    {
        $value = Mage::getStoreConfig('grafikentwicklung_tracking/emty_cart_tracking/mail_to_mailaddress');
        if (empty($value)) {
            return null;
        }

        $value = Mage::getStoreConfig('trans_email/ident_' . $value . '/email');
        return $value;
    }


//    /**
//     * @return string|null
//     */
//    public function getEmptyCartTrackingSendMailCCMailAddress()
//    {
//        $value = Mage::getStoreConfig('grafikentwicklung_tracking/emty_cart_tracking/mail_cc_mailaddress');
//        return $value;
//    }


}