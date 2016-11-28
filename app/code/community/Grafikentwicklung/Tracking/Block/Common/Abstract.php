<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Common_Abstract
 */
class Grafikentwicklung_Tracking_Block_Common_Abstract extends Mage_Core_Block_Template
{
    const EXCLUDED_DOMAIN_SEE_SETTINGS = 'EXCLUDED_DOMAIN_SEE_SETTINGS';

    /** @var Grafikentwicklung_Tracking_Model_Salestracking */
    private $model = null;


    /**
     * @return Grafikentwicklung_Tracking_Helper_Data
     */
    public function getTrackingHelper()
    {
        return Mage::helper('grafikentwicklung_tracking');
    }


    /**
     * @return Grafikentwicklung_Tracking_Model_Salestracking
     */
    protected function getModelWithLoggedDataBySession()
    {
        if ($this->model === null) {
            /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
            $model = $this->getTrackingHelper()->getSalesTrackingModelBySessionId();
            if ($model) {
                $model->getLoggedDataBySession();
                $this->model = $model;
            }
        }

        return $this->model;

    }

}
