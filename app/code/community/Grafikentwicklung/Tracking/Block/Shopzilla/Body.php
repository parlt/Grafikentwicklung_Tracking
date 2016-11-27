<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Shopzilla_Body
 */
class Grafikentwicklung_Tracking_Block_Shopzilla_Body extends Grafikentwicklung_Tracking_Block_Common_Abstract
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('grafikentwicklung/tracking/shopzilla/body.phtml');
    }


    /**
     * @return array
     */
    public function getShopZillaSalesTrackingData()
    {
        /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
        $model = $this->getModelWithLoggedDataBySession();
        if ($model) {
            $data = [
                'cust_type' => !empty($model->getData('transaction_is_new_customer')) ? '1' : '0',
                'order_value' => $model->getData('transaction_total'),
                'order_id' => $model->getData('transaction_id'),
                'units_ordered' => $model->getData('transaction_units_ordered')
            ];

            return $data;
        }

        return [
            'cust_type' => '0',
            'order_value' => 0,
            'order_id' => 0,
            'units_ordered' => 0
        ];
    }
}
