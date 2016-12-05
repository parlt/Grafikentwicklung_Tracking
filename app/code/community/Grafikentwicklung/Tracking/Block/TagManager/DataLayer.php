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
     * @param string $type
     * @return array
     */
    public function getTagManagerSalesTrackingData($type)
    {
        /** @var Grafikentwicklung_Tracking_Model_Salestracking $model */
        $model = $this->getModelWithLoggedDataBySession($type);
        if ($model) {
            $data = [
                'transactionId' => $this->getPreparedData($model, 'transaction_id', 'string'),
                'transactionTotal' => $this->getPreparedData($model, 'transaction_total', 'number'),
                'transactionTax' => $this->getPreparedData($model, 'transaction_tax', 'number'),
                'transactionShipping' => $this->getPreparedData($model, 'transaction_shipping', 'number'),
                'productName' => ''

            ];


            if ($type === Grafikentwicklung_Tracking_Model_Salestracking::TYPE_PRODUCT) {
                $data['transactionSkus'] = $model->getData('transaction_product_list_sku');
                $data['transactionProducts'] = '';
                $data['productName'] = $model->getData('productName');
            } else {
                $data['transactionSkus'] = json_encode(explode(',', $model->getData('transaction_product_list_sku')));
                $data['transactionProducts'] = json_encode(
                    $model->decodeSerializedDataToArray(
                        $model->getData('transaction_product_data')
                    ), JSON_PRETTY_PRINT
                );
            }


            if ($this->isCategoryRelevant($type)) {
                $data['category'] = $this->getPreparedData($model, 'category', 'string');
            }

            return $data;
        }

        return [
            'transactionId' => '',
            'transactionTotal' => 0,
            'transactionTax' => 0,
            'transactionShipping' => 0,
            'transactionProducts' => [],
            'transactionSkus' => '[]',
            'category' => '',
            'productName' => ''
        ];
    }


    /**
     * @param Grafikentwicklung_Tracking_Model_Salestracking $model
     * @param string $key
     * @param string $type
     * @return array|int|null|string
     */
    private function getPreparedData(Grafikentwicklung_Tracking_Model_Salestracking $model, $key, $type)
    {
        $data = $model->getData($key);
        if ($data) {
            return $data;
        }

        if (!$data) {
            if ($type === 'number') {
                return 0;
            }
            if ($type == 'string') {
                return '';
            }

            if ($type == 'array') {
                return [];
            }
        }

        return null;
    }


    /**
     * @param $type
     * @return bool
     */
    private function isCategoryRelevant($type)
    {
        switch ($type) {
            case Grafikentwicklung_Tracking_Model_Salestracking::TYPE_CATEGORY:
            case Grafikentwicklung_Tracking_Model_Salestracking::TYPE_PRODUCT:
                return true;
            default:
                break;
        }

        return false;
    }
}