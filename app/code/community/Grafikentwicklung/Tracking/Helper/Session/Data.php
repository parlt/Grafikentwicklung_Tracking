<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Helper_Session_Data
 */
class Grafikentwicklung_Tracking_Helper_Session_Data extends Mage_Core_Helper_Abstract
{

    /**
     * @return Mage_Customer_Model_Session
     */
    public function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }


    /**
     * @return Mage_Core_Model_Session
     */
    public function getCoreSession()
    {
        return Mage::getSingleton('core/session');
    }

    /**
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }


    /**
     * @return string
     */
    public function getStoreCode()
    {
        return Mage::app()->getStore()->getCode();
    }


    /**
     * @return string
     */
    public function getCustomerSessionId()
    {
        return $this->getCustomerSession()->getSessionId();
    }


    /**
     * @return string
     */
    public function getSessionName()
    {
        return $this->getCustomerSession()->getSessionName();
    }


    /**
     * @return string
     */
    public function getCookieDomain()
    {
        return $this->getCustomerSession()->getCookieDomain();
    }


    /**
     * @return int
     */
    public function getCookieLifetime()
    {
        return $this->getCustomerSession()->getCookieLifetime();
    }


    /**
     * @return string|null
     */
    public function getRemoteAddress()
    {

        /** @var array $validatorData */
        $validatorData = $this->getValidatorData();

        if (!isset($validatorData['remote_addr'])) {
            return null;
        }

        return $validatorData['remote_addr'];
    }


    /**
     * @return string|null
     */
    public function getHttpVia()
    {
        /** @var array $validatorData */
        $validatorData = $this->getValidatorData();

        if (!isset($validatorData['http_via'])) {
            return null;
        }

        return $validatorData['http_via'];
    }


    /**
     * @return string|null
     */
    public function getHttpXForwardedFor()
    {
        /** @var array $validatorData */
        $validatorData = $this->getValidatorData();

        if (!isset($validatorData['http_x_forwarded_for'])) {
            return null;
        }

        return $validatorData['http_x_forwarded_for'];
    }


    /**
     * @return string|null
     */
    public function getHttpUserAgent()
    {
        /** @var array $validatorData */
        $validatorData = $this->getValidatorData();

        if (!isset($validatorData['http_user_agent'])) {
            return null;
        }

        return $validatorData['http_user_agent'];
    }


    /**
     * @return string
     */
    public function getMessagesCollection()
    {
        return '';
    }


    /**
     * @return array
     */
    public function getValidatorData()
    {
        $session = $this->getCustomerSession();
        if (!$session) {
            return [];
        }

        $validatorData = $session->getValidatorData();

        if (!$validatorData) {
            return [];
        }

        return $validatorData;
    }

    /**
     * @return string
     */
    public function getValidatorSerialized()
    {
        $session = $this->getCustomerSession();
        if (!$session) {
            return '';
        }

        $validatorData = $session->getValidatorData();

        if (!$validatorData) {
            return '';
        }

        $json = json_encode($validatorData);
        $value = $serialized = serialize($json);
        return $value;
    }

    /**
     * @return array
     */
    public function getSessionDataAsArray(){
        return [
            'store_code' => $this->getStoreCode(),
            'session_id' => $this->getCustomerSessionId(),
            'session_name' => $this->getSessionName(),
            'cookie_domain' => $this->getCookieDomain(),
            'cookie_lifetime' => $this->getCookieLifetime(),
            'customer_id' => '',
            'session_remote_address' => $this->getRemoteAddress(),
            'session_http_via' => $this->getHttpVia(),
            'session_http_x_forwarded_for' => $this->getHttpXForwardedFor(),
            'session_http_user_agent' => $this->getHttpUserAgent(),
            'session_validator_data' => $this->getValidatorSerialized()

        ];
    }

}