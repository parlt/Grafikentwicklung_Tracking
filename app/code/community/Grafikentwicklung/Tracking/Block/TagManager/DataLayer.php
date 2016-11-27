<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_TagManager_DataLayer
 */
class Grafikentwicklung_Tracking_Block_TagManager_DataLayer extends Grafikentwicklung_Tracking_Block_TagManager_Abstract
{
    
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('grafikentwicklung/tracking/tagmanger/datalayer.phtml');
    }


     /**
     * @return array
     */
    public function getTagManagerSalesTrackingData()
    {
        /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
        $model = $this->getModelWithLoggedDataBySession();
        if ($model) {
            $data = [
                'transactionId' => $model->getData('transaction_id'),
                'transactionTotal' => $model->getData('transaction_total'),
                'transactionTax' => $model->getData('transaction_tax'),
                'transactionShipping' => $model->getData('transaction_shipping'),
                'transactionProducts' => json_encode(
                    $model->decodeSerializedDataToArray(
                        $model->getData('transaction_product_data')
                    ), JSON_PRETTY_PRINT
                )
            ];
            return $data;
        }

        return [
            'transactionId' => 0,
            'transactionTotal' => 0,
            'transactionTax' => 0,
            'transactionShipping' => 0,
            'transactionProducts' => 0
        ];
    }
}