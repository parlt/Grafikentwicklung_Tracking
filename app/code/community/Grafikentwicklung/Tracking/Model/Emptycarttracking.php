<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Model_Emptycarttracking
 */
class Grafikentwicklung_Tracking_Model_Emptycarttracking extends Grafikentwicklung_Tracking_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('grafikentwicklung_tracking/emptycarttracking');
    }


    /**
     * @return Grafikentwicklung_Tracking_Helper_Data
     */
    protected function getTrackingHelper()
    {
        return Mage::helper('grafikentwicklung_tracking');
    }


    /**
     * @return Grafikentwicklung_Tracking_Model_Resource_Emptycarttracking|Mage_Core_Model_Mysql4_Abstract
     */
    protected function _getResource()
    {
        return parent::_getResource();
    }


    /**
     * @return $this
     */
    public function setIsAccessingCart()
    {
        $this->setData('is_accessing_cart', '1');
        return $this;
    }


    /**
     * @return $this
     */
    public function setIsEmptyCart()
    {
        $this->setData('is_empty_cart', '1');
        return $this;
    }


    /**
     * @return $this
     */
    public function logEmptyCart()
    {
        $this->storeIsAccessingCart();
        $this->setIsEmptyCart();
        $this->save();

        if ($this->getTrackingHelper()->canSendEmptyCartMail()) {
            $this->sendEmail();
        }

        if ($this->getTrackingHelper()->isEmptyCartTrackingWriteLogFileEnabled()
            && $this->getTrackingHelper()->getEmptyCartTrackingLogFileName()
        ) {
            $this->writeEmptyCartTrackingLogFile();

        }
        return $this;
    }


    /**
     * @return $this
     */
    public function sendEmail()
    {
        $data = $this->getDataForMail();

        $subject = $this->getTrackingHelper()->getEmptyCartTrackingSendMailSubject();

        $fromMail = $this->getTrackingHelper()->getEmptyCartTrackingSendMailFromMailAddress();
        $toMail = $this->getTrackingHelper()->getEmptyCartTrackingSendMailToMailAddress();

        $senderData = ['name' => $subject, 'email' => $fromMail];

        $this->sendTransactionEmail(
            'grafikentwicklung_tracking_empty_cart_notification',
            $senderData,
            $toMail,
            $toMail,
            $subject,
            $data
        );
        return $this;
    }


    /**
     * @return $this
     */
    public function writeEmptyCartTrackingLogFile()
    {
        $data = $this->getDataForLogFile();
        $logFileName = $this->getTrackingHelper()->getEmptyCartTrackingLogFileName();
        Mage::log(print_r($data, true), Zend_Log::INFO, $logFileName);
        return $this;
    }


    /**
     * @return $this
     */
    public function storeIsAccessingCart()
    {
        $this->setData('is_accessing_cart', '1');
        $this->save();
        $this->load($this->id);
        return $this;
    }


    /**
     * @param string $sessionId
     * @param string $storeCode
     * @param string $remoteAddress
     * @param string $userAgent
     * @return $this
     */
    public function loadBySessionAndStoreData($sessionId, $storeCode, $remoteAddress, $userAgent)
    {
        $this->_getResource()->loadBySessionAndStoreData(
            $this, $sessionId, $storeCode, $remoteAddress, $userAgent
        );

        return $this;
    }


    /**
     * @param string $url
     * @return $this
     */
    public function storeRequestedUrl($url)
    {
        /** @var string $requestedUrls */
        $requestedUrls = $this->getData('session_requested_urls');

        try {
            /** @var array $requestedUrlsData */
            $requestedUrlsData = $this->decodeSerializedDataToArray($requestedUrls);
            $requestedUrlsData[date('Y-m-d H:i:s')] = $url;

            /** @var string $serialized */
            $serialized = $this->encodeArrayToSerializedData($requestedUrlsData);

            $this->setData('session_requested_urls', $serialized);
            $this->save();
            $this->load($this->id);

        } catch (Exception $e) {
            Mage::log($e->getMessage(), 'exception.log');
        }
        return $this;
    }


    /**
     * @return array
     */
    public function getDataForLogFile()
    {
        $data = $this->getData();
        $data['session_requested_urls'] = isset($data['session_requested_urls'])
            ? $requestedUrlsData = $this->decodeSerializedDataToArray($data['session_requested_urls'])
            : [];

        $requestedUrls = '';
        foreach ($data['session_requested_urls'] as $key => $value) {
            $requestedUrls .= '' . $key . ': ' . $value . "\n";
        }
        $data['session_requested_urls'] = "\n" . $requestedUrls . "\n";

        $data['session_validator_data'] = !empty($data['session_validator_data'])
            ? $requestedUrlsData = $this->decodeSerializedDataToArray($data['session_validator_data'])
            : [];

        $validatorData = '';
        foreach ($data['session_validator_data'] as $key => $value) {
            $validatorData .= '' . $key . ': ' . $value . "\n";
        }
        $data['session_validator_data'] = "\n" . $validatorData . "\n";

        if (empty($data)) {
            $data = [];
        }

        return $data;
    }


    /**
     * @return array
     */
    public function getDataForMail()
    {
        $data = $this->getData();
        $data['session_requested_urls'] = isset($data['session_requested_urls'])
            ? $requestedUrlsData = $this->decodeSerializedDataToArray($data['session_requested_urls'])
            : [];

        $requestedUrls = '';
        foreach ($data['session_requested_urls'] as $key => $value) {
            $requestedUrls .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
        }
        $data['session_requested_urls'] = '<table>' . $requestedUrls . '</table>';

        $data['session_validator_data'] = !empty($data['session_validator_data'])
            ? $requestedUrlsData = $this->decodeSerializedDataToArray($data['session_validator_data'])
            : [];

        $validatorData = '';
        foreach ($data['session_validator_data'] as $key => $value) {
            $validatorData .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
        }
        $data['session_validator_data'] = '<table>' . $validatorData . '</table>';

        if (empty($data)) {
            $data = [];
        }

        return $data;
    }

}