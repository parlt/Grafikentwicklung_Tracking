<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Moebelde_Body
 */
class Grafikentwicklung_Tracking_Block_Moebelde_Body extends Grafikentwicklung_Tracking_Block_Common_Abstract
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('grafikentwicklung/tracking/moebelde/body.phtml');
    }


    /**
     * @return array
     */
    public function getMoebeldeSalesTrackingData()
    {
        /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
        $model = $this->getModelWithLoggedDataBySession(Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CHECKOUT);
        if ($model) {

            /** @var int|float $calculatedNetto */
            $calculatedNetto = $model->getData('transaction_total')
                - $model->getData('transaction_tax')
                - $model->getData('transaction_shipping');

            $data = [
                '_umsatz' => $calculatedNetto,
                '_versandkosten' => $model->getData('transaction_shipping'),
                '_artikelliste' => $model->getData('transaction_product_list_sku')
            ];

            return $data;
        }

        return [
            '_umsatz' => 0,
            '_versandkosten' => 0,
            '_artikelliste' => 0,
        ];
    }
}
