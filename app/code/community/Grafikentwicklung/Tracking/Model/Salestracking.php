<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Model_Salestracking
 */
class Grafikentwicklung_Tracking_Model_Salestracking extends Grafikentwicklung_Tracking_Model_Abstract
{
    /** @var string */
    private $sessionId;

    protected function _construct()
    {
        $this->_init('grafikentwicklung_tracking/salestracking');
    }


    /**
     * @return Grafikentwicklung_Tracking_Helper_Data
     */
    protected function getTrackingHelper()
    {
        return Mage::helper('grafikentwicklung_tracking');
    }


    /**
     * @return Grafikentwicklung_Tracking_Helper_Session_Data
     */
    protected function getSessionHelper()
    {
        return Mage::helper('grafikentwicklung_tracking_session');
    }


    /**
     * @param $sessionId
     * @return $this
     */
    public function loadBySessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        $this->_getResource()->loadBySessionId($this, $sessionId);
        return $this;
    }


    /**
     * @return Grafikentwicklung_Tracking_Model_Resource_Salestracking|Mage_Core_Model_Mysql4_Abstract
     */
    protected function _getResource()
    {
        return parent::_getResource();
    }


    /**
     * @return mixed
     */
    private function getShoppingPortal()
    {
        return $this->getData('shopping_portal');
    }


    /**
     * @param $orderId
     * @return Mage_Sales_Model_Order
     */
    private function getOrderByOrderId($orderId)
    {
        return Mage::getModel('sales/order')->load($orderId);
    }


    /**
     * @param null $orderId
     * @return $this
     */
    public function logTransactionData($orderId = null)
    {

        $this->storeLogData($orderId);

        if ($this->getTrackingHelper()->canSendSalesTrackingMail()) {
            $this->sendEmail();
        }

        if ($this->getTrackingHelper()->isSalesTrackingWriteLogFileEnabled()
            && $this->getTrackingHelper()->getSalesTrackingLogFileName()
        ) {
            $this->writeSalesTrackingLogFile();
        }

        return $this;
    }


    /**
     * @return $this
     */
    public function sendEmail()
    {
        $data = $this->getDataForMail();

        $subject = $this->getTrackingHelper()->getSalesTrackingSendMailSubject();
        $subject = $subject . ' | ' . $this->getData('shopping_portal');
        $fromMail = $this->getTrackingHelper()->getSalesTrackingSendMailFromMailAddress();
        $toMail = $this->getTrackingHelper()->getSalesTrackingSendMailToMailAddress();

        $senderData = ['name' => $subject, 'email' => $fromMail];

        $this->sendTransactionEmail(
            'grafikentwicklung_tracking_sales_tracking_notification',
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
    public function writeSalesTrackingLogFile()
    {
        $data = $this->getDataForLogFile();
        $logFileName = $this->getTrackingHelper()->getSalesTrackingLogFileName();
        Mage::log(print_r($data, true), Zend_Log::INFO, $logFileName);
        return $this;
    }


    /**
     * @param $number
     * @return string
     */
    private function formatNumber($number)
    {
        return number_format($number, '2', '.', ',');
    }


    /**
     * @param null|int $orderId
     * @return $this
     */
    private function storeLogData($orderId = null)
    {
        $this->getLoggedDataBySession($orderId);
        $this->save();

        return $this;
    }


    /**
     * @param null|int $orderId
     * @return $this
     */
    public function getLoggedDataBySession($orderId = null){

        if ($orderId === null) {
            $orderId = $this
                ->getSessionHelper()
                ->getCheckoutSession()
                ->getLastOrderId();
        }

        /** @var Mage_Sales_Model_Order $order */
        $order = $this->getOrderByOrderId($orderId);

        if ($order) {

            /** @var Mage_Sales_Model_Order_Item[] $items */
            $items = $order->getAllVisibleItems();

            $this
                ->setData('transaction_id', $order->getIncrementId())
                ->setData('transaction_tax', $this->formatNumber($order->getTaxAmount()))
                ->setData('transaction_total', $this->formatNumber($order->getGrandTotal()))
                ->setData('transaction_shipping', $this->formatNumber($order->getShippingAmount()))
                ->setData('transaction_currency', Mage::app()->getStore()->getCurrentCurrencyCode())
                ->setData('transaction_is_new_customer', empty($order->getCustomerId()));

            /** @var string[] $transactionProductListSku */
            $transactionProductListSku = [];

            /** @var int $transactionUnitsOrdered */
            $transactionUnitsOrdered = 0;

            /** @var string[] $transactionProducts */
            $transactionProducts = [];

            /** @var Mage_Sales_Model_Order_Item $item */
            foreach ($items as $item) {
                $sku = $item->getSku();
                $qty = (int)$item->getQtyOrdered();
                $dataItem = [
                    'sku' => $sku,
                    'name' => str_replace('&nbsp;',' ',$item->getName()),
                    'price' => $this->formatNumber($item->getPrice()),
                    'quantity' => $qty,
                    'productUrl' => $item->getProduct()->getProductUrl()

                ];
                $transactionUnitsOrdered = $transactionUnitsOrdered + $qty;
                $transactionProducts[] = $dataItem;
                $transactionProductListSku[] = $sku;

            }
            $this
                ->setData('transaction_product_list_sku', implode(',', $transactionProductListSku))
                ->setData('transaction_units_ordered', $transactionUnitsOrdered)
                ->setData('transaction_product_data', $this->encodeArrayToSerializedData($transactionProducts));
        }

        return $this;
    }


    /**
     * @param $url
     * @return $this
     */
    public function storePortal($url)
    {
        if (empty($this->getShoppingPortal()) && $this->getTrackingHelper()->isSalesTrackingEnabled()) {
            $referrers = $this->getEnabledTrackingReferrersAsArray();
            $shoppingPortal = $this->getCurrentReferrerByUrl($referrers, $url);
            if ($shoppingPortal) {
                $this
                    ->setData('shopping_portal', $shoppingPortal)
                    ->setData('session_id', $this->getSessionHelper()->getCustomerSessionId())
                    ->setData('store_code', $this->getSessionHelper()->getStoreCode());

                $this->save();
            }
        }

        return $this;
    }


    /**
     * @param array $referrers
     * @param $url
     * @return mixed|null
     */
    private function getCurrentReferrerByUrl(array $referrers, $url)
    {
        /** @var array $urlData */
        $urlData = parse_url($url);
        if (isset($urlData['query'])) {
            $query = $urlData['query'];
            foreach ($referrers as $portal => $param) {
                if (strpos($query, 'referrer=' . $param) !== false) {
                    return $param;
                }
            }
        }
        return null;
    }


    /**
     * @return array
     */
    private function getEnabledTrackingReferrersAsArray()
    {
        $referrers = [];
        if ($this->getTrackingHelper()->isSalesTrackingShopZillaEnabled()) {
            $shopZillaReferrerer = $this->getTrackingHelper()->getSalesTrackingShopZillaReferrer();
            if (!empty($shopZillaReferrerer)) {
                $referrers['shopzilla'] = $shopZillaReferrerer;
            }
        }

        if ($this->getTrackingHelper()->isSalesTrackingGoogleEnabled()) {
            $googleReferrerer = $this->getTrackingHelper()->getSalesTrackingGoogleReferrer();
            if (!empty($googleReferrerer)) {
                $referrers['google'] = $googleReferrerer;
            }
        }

        if ($this->getTrackingHelper()->isSalesTrackingBilligerDeEnabled()) {
            $billigerDeReferrer = $this->getTrackingHelper()->getSalesTrackingBilligerDeReferrer();
            if (!empty($billigerDeReferrer)) {
                $referrers['billiger'] = $billigerDeReferrer;
            }
        }

        if ($this->getTrackingHelper()->isSalesTrackingMoebelDeEnabled()) {
            $moebelDeReferrer = $this->getTrackingHelper()->getSalesTrackingMoebelDeReferrer();
            if (!empty($moebelDeReferrer)) {
                $referrers['moebel'] = $moebelDeReferrer;
            }
        }

        if ($this->getTrackingHelper()->isSalesTrackingPreisroboterEnabled()) {
            $preisroboterReferrer = $this->getTrackingHelper()->getSalesTrackingPreisroboterReferrer();
            if (!empty($preisroboterReferrer)) {
                $referrers['moebel'] = $preisroboterReferrer;
            }
        }

        if ($this->getTrackingHelper()->isSalesTrackingPreisroboterEnabled()) {
            $preisroboterReferrer = $this->getTrackingHelper()->getSalesTrackingPreisroboterReferrer();
            if (!empty($preisroboterReferrer)) {
                $referrers['preisroboter'] = $preisroboterReferrer;
            }
        }

        return $referrers;
    }


    /**
     * @return array
     */
    public function getDataForMail()
    {
        $data = $this->getDataForLogFileAndMail();


        return $data;
    }


    /**
     * @return array
     */
    public function getDataForLogFile()
    {
        $data = $this->getDataForLogFileAndMail("\n");

        return $data;
    }


    /**
     * @param string $replacement
     * @return array|mixed
     */
    private function getDataForLogFileAndMail($replacement = '<br>')
    {
        $data = $this->getData();

        if (empty($data)) {
            $data = [];
        }

        /**
         * @var string $key
         * @var  int|string|float $value
         */
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'transaction_tax':
                case 'transaction_total':
                case 'transaction_shipping':
                    $value = $this->formatNumber($value);
                    break;
                case 'transaction_product_list_sku':
                    $value = str_replace(',', $replacement, $value);
                    break;
                default:
                    break;
            }

            $data[$key] = $value;
        }

        return $data;
    }
}