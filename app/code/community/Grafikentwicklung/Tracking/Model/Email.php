<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        23.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Model_Email
 */
class Grafikentwicklung_Tracking_Model_Email extends Mage_Core_Model_Email_Template
{
    /**
     * Send email to recipient
     *
     * @param string $templateId template identifier (see config.xml to know it)
     * @param array $sender sender name with email ex. array('name' => 'John D.', 'email' => 'email@ex.com')
     * @param string $email recipient email address
     * @param string $name recipient name
     * @param string $subject email subject
     * @param array $params data array that will be passed into template
     */
    public function sendEmail($templateId, $sender, $email, $name, $subject, $params = array())
    {
        /** @var Grafikentwicklung_Tracking_Model_Email $object */
        $object = $this->setDesignConfig(['area' => 'frontend', 'store' => $this->getDesignConfig()->getStore()]);
        $object->setTemplateSubject($subject);
        $object->sendTransactional(
            $templateId,
            $sender,
            $email,
            $name,
            $params
        );
    }

}