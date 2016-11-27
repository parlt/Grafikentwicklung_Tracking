<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Model_Abstract
 */
class Grafikentwicklung_Tracking_Model_Abstract extends Mage_Core_Model_Abstract
{

    public function sendTransactionEmail($templateId, $senderData, $toMail, $toName, $subject, $data)
    {
        /** @var Grafikentwicklung_Tracking_Model_Email $email */
        $email = Mage::getModel('grafikentwicklung_tracking/email');
        $email->sendEmail(
            $templateId,
            $senderData,
            $toMail,
            $toName,
            $subject,
            $data
        );
    }


    /**
     * @param string $value
     * @return array|mixed
     */
    public function decodeSerializedDataToArray($value)
    {
        if (!$value) {
            return [];
        }
        try {
            /** @var string $unSerialized */
            $unSerialized = unserialize($value);

            /** @var array $data */
            $data = json_decode($unSerialized, true);

            if (!$data) {
                return [];
            }

            return $data;

        } catch (Exception $e) {
            Mage::log($e->getMessage(), 'exception.log');
        }
        return [];
    }


    /**
     * @param array $data
     * @return null|string
     */
    protected function encodeArrayToSerializedData(array $data)
    {
        try {
            /** @var string $json */
            $json = json_encode($data);
            if (!$json) {
                return null;
            }

            /** @var string $serialized */
            $serialized = serialize($json);
            if (!$serialized) {
                return null;
            }

            return $serialized;
        } catch (Exception $e) {
            Mage::log($e->getMessage(), 'exception.log');
        }

        return null;

    }
}