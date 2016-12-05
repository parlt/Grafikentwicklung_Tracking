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
     * @param string $type
     * @return Grafikentwicklung_Tracking_Model_Salestracking|null
     */
    protected function getModelWithLoggedDataBySession($type)
    {
        if ($type !== Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CHECKOUT
            && $type !== Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CART
            && $type !== Grafikentwicklung_Tracking_Model_Salestracking::TYPE_SEARCH
            && $type !== Grafikentwicklung_Tracking_Model_Salestracking::TYPE_PRODUCT
            && $type !== Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CATEGORY
            && $type !== Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CMS
            && $type !== Grafikentwicklung_Tracking_Model_Salestracking::TYPE_START

        ) {
            return null;
        }

        /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
        $model = $this->getTrackingHelper()->getSalesTrackingModelBySessionId();
        if ($model) {

            if ($type === Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CHECKOUT) {
                $model->getLoggedDataByCheckoutSession();
            }

            if ($type === Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CART) {
                $model->getCurrentDataByCart();
            }

            if ($type === Grafikentwicklung_Tracking_Model_Salestracking::TYPE_SEARCH) {
                $model->getCurrentDataBySearch();
            }

            if ($type === Grafikentwicklung_Tracking_Model_Salestracking::TYPE_PRODUCT) {
                $model->getCurrentDataByProduct();
            }

            if ($type === Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CATEGORY) {
                $model->getCurrentDataByCategory();
            }

            if ($type === Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CMS) {
                $model->getCurrentDataByCms();
            }
            $this->model = $model;
        }

        return $this->model;

    }

}
