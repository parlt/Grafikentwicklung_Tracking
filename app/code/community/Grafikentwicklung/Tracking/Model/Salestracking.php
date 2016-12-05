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

    const TYPE_CHECKOUT = 'checkout';
    const TYPE_CART = 'cart';
    const TYPE_CATEGORY = 'category';
    const TYPE_CMS = 'cms';
    const TYPE_START = 'start';
    const TYPE_PRODUCT = 'product';
    const TYPE_SEARCH = 'search';

    /** @var Magento_Db_Adapter_Pdo_Mysql $readConnection */
    protected $readConnection = null;


    /** @var string */
    private $sessionId;


    /**
     * @return Magento_Db_Adapter_Pdo_Mysql|Varien_Db_Adapter_Interface
     */
    protected function getReadConnection()
    {
        if ($this->readConnection === null) {
            /** @var Mage_Core_Model_Resource $resource */
            $resource = Mage::getSingleton('core/resource');
            /** @var Magento_Db_Adapter_Pdo_Mysql $readConnection */
            $this->readConnection = $resource->getConnection('core_read');
        }

        return $this->readConnection;
    }


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
        $portal = $this->getData('shopping_portal');

        if (empty($portal)) {
            return 'default';
        }

        return $portal;
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
     * @param null|int $orderId
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

        $subject = $subject . ' | ' . $this->getData('transaction_id') . ' | ' . $this->getShoppingPortal();
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
        return number_format($number, '2', '.', '');
    }


    /**
     * @param null|int $orderId
     * @return $this
     */
    private function storeLogData($orderId = null)
    {
        $this->getLoggedDataByCheckoutSession($orderId);
        $this->save();

        return $this;
    }


    /**
     * @return $this
     */
    public function getCurrentDataByCart()
    {
        /** @var Mage_Checkout_Helper_Cart $helper */
        $helper = $this->getSessionHelper()->getCartHelper();

        /** @var Mage_Sales_Model_Resource_Quote_Item_Collection $items */
        $items = $helper->getCart()->getItems();

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $helper->getCart()->getQuote();

        $this->setData('transaction_total', $this->formatNumber($quote->getGrandTotal()));

        /** @var string[] $skus */
        $skus = [];
        /** @var Mage_Sales_Model_Quote_Item $item */
        foreach ($items as $item) {
            $skus[] = $this->getQuoteItemSku($item);
        }

        $this->setData('transaction_product_list_sku', implode(',', $skus));

        return $this;
    }


    /**
     * @return $this
     */
    public function getCurrentDataBySearch()
    {
        /** @var Grafikentwicklung_Customizing_Model_CatalogSearch_Layer|Mage_CatalogSearch_Model_Layer $currentLayer */
        $currentLayer = Mage::registry('current_layer');


        if ($this->getTrackingHelper()->isGrafikentwicklungCustomoptionsEnabled()) {
            $currentLayer->setCustomOptionsHandlingEnabled();
        }

        /** @var Mage_CatalogSearch_Model_Resource_Fulltext_Collection $products */
        $products = $currentLayer->getProductCollection();

        if ($this->getTrackingHelper()->isGrafikentwicklungCustomoptionsEnabled()) {
            $currentLayer->setCustomOptionsHandlingEnabled(false);
        }


        if (!$products || $products->count() < 1) {
            return $this;
        }

        $this->prepareProductCollection($products);
        $this->prepareProductsData($products);

        return $this;
    }


    /**
     * @return $this
     */
    public function getCurrentDataByProduct()
    {
        /** @var Grafikentwicklung_Customizing_Model_Catalog_Product|Mage_Model_Catalog_Product $currentProduct */
        $currentProduct = Mage::registry('current_product');

        if (!$currentProduct){
            return $this;
        }

        /** @var Grafikentwicklung_Customizing_Model_Catalog_Category|Mage_Catalog_Model_Category $currentCategory */
        $currentCategory = Mage::registry('current_category');

        if ($this->getTrackingHelper()->isGrafikentwicklungCustomoptionsEnabled()) {
            $currentProduct->setCustomOptionsHandlingEnabled();
        }

        $sku = $currentProduct->getData('sku');
        $price = $currentProduct->getData('price');
        $name = str_replace('&nbsp;',' ',$currentProduct->getName());

        if ($this->getTrackingHelper()->isGrafikentwicklungCustomoptionsEnabled()) {
            $currentProduct->setCustomOptionsHandlingEnabled(false);
        }

        $this->setData('transaction_total', $this->formatNumber($price));
        $this->setData('transaction_product_list_sku', $sku);

        $this->setData('productName', $name);
        $this->setData('category', $this->getCategoryPathByCategory($currentCategory));
        return $this;
    }


    /**
     * @return $this
     */
    public function getCurrentDataByCategory()
    {
        /** @var Grafikentwicklung_Customizing_Model_Catalog_Category|Mage_Catalog_Model_Category $currentCategory */
        $currentCategory = Mage::registry('current_category');
        if ($this->getTrackingHelper()->isGrafikentwicklungCustomoptionsEnabled()) {
            $currentCategory->setCustomOptionsHandlingEnabled();
        }

        /** @var Mage_Catalog_Model_Resource_Product_Collection $products */
        $products = $currentCategory->getProductCollection();

        if ($this->getTrackingHelper()->isGrafikentwicklungCustomoptionsEnabled()) {
            $currentCategory->setCustomOptionsHandlingEnabled(false);
        }

        if (!$products || $products->count() < 1) {
            return $this;
        }

        $this->prepareProductCollection($products);
        $this->prepareProductsData($products);

        $this->setData('category', $this->getCategoryPathByCategory($currentCategory));
        return $this;
    }


    /**
     * @return $this
     */
    public function getCurrentDataByCms()
    {
        if ($this->getTrackingHelper()->isAmastyXlandigEnabled() && Mage::registry('amlanding_page') !== null) {
            /** @var Amasty_Xlanding_Model_Page $xpageLanding */
            $xpageLanding = Mage::registry('amlanding_page');

            /** @var Mage_Catalog_Model_Resource_Product_Collection $products */
            $products = $xpageLanding->applyPageRules();

            if (!$products || $products->count() < 1) {
                return $this;
            }

            $this->prepareProductCollection($products);
            $this->prepareProductsData($products);
        }

        return $this;
    }


    /**
     * @return $this
     */
    public function getCurrentDataByStart()
    {
        $this->getCurrentDataByCms();

        return $this;
    }


    /**
     * @param Mage_CatalogSearch_Model_Resource_Fulltext_Collection|Mage_Catalog_Model_Resource_Product_Collection $collection
     * @return $this
     */
    private function prepareProductCollection($collection)
    {
        $collection->addAttributeToSelect(['price', 'visibility', 'status'])
            ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            ->addAttributeToFilter('status', ['eq' => 1]);

        return $this;
    }


    /**
     * @param Mage_CatalogSearch_Model_Resource_Fulltext_Collection|Mage_Catalog_Model_Resource_Product_Collection $collection
     * @return $this
     */
    private function prepareProductsData($collection)
    {
        /** @var string[] $skus */
        $skus = [];

        /** @var float[] $prices */
        $prices = [];

        /** @var Mage_Catalog_Model_Product $product */
        foreach ($collection as $product) {
            $skus[] = $product->getData('sku');
            $prices[] = $product->getData('price');
        }

        $total = 0;
        foreach ($prices as $price) {
            $total = $total + $price;
        }

        $this->setData('transaction_total', $this->formatNumber($total));
        $this->setData('transaction_product_list_sku', implode(',', $skus));

        return $this;
    }


    /**
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    protected function getCategoryPathByCategory($category)
    {
        /** @var string $path */
        $path = $category->getPath();

        /** @var string[] $categoryIds */
        $categoryIds = explode('/', $path);

        // remove root id and default id
        $categoryIds = array_slice($categoryIds, 2);

        /** @var string[] $categoryNames */
        $categoryNames = [];

        /** @var string $categoryId */
        foreach ($categoryIds as $categoryId) {

            /** @var Mage_Catalog_Model_Category $selectedCategory */
            $selectedCategory = Mage::getModel('catalog/category')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($categoryId);

            $categoryNames[] = $selectedCategory->getName();
        }

        return implode(' > ', $categoryNames);
    }


    /**
     * @param Mage_Catalog_Model_Product $product
     * @return mixed
     */
    protected function getProductSku(Mage_Catalog_Model_Product $product)
    {
        return str_replace('-', '', $product->getSku());
    }


    /**
     * @param Mage_Sales_Model_Quote_Item $item
     * @return string
     */
    protected function getQuoteItemSku(Mage_Sales_Model_Quote_Item $item)
    {
        return str_replace('-', '', $item->getSku());
    }


    /**
     * @param null|int $orderId
     * @return $this
     */
    public function getLoggedDataByCheckoutSession($orderId = null)
    {
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
                    'name' => str_replace('&nbsp;', ' ', $item->getName()),
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
            if (!$shoppingPortal) {
                $shoppingPortal = 'default';
            }

            $this
                ->setData('shopping_portal', $shoppingPortal)
                ->setData('session_id', $this->getSessionHelper()->getCustomerSessionId())
                ->setData('store_code', $this->getSessionHelper()->getStoreCode());

            $this->save();
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