<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking_Grid
 */
class Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('grid_id');
        // $this->setDefaultSort('COLUMN_ID');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);

    }


    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        /** @var Grafikentwicklung_Tracking_Model_Resource_Salestracking_Collection|Varien_Data_Collection $collection */
        $collection = Mage::getModel('grafikentwicklung_tracking/salestracking')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }


    /**
     * @return Mage_Adminhtml_Block_Widget_Grid|Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking_Grid
     */
    protected function _prepareColumns()
    {
        /** @var Grafikentwicklung_Tracking_Helper_Data $helper */
        $helper = Mage::helper('grafikentwicklung_tracking');

        $this->addColumn('id', [
            'header' => $helper->__('id'),
            'index' => 'id',
            'width' => '80'
        ]);

        $this->addColumn('store_code', [
            'header' => $helper->__('Store'),
            'index' => 'store_code',
            'width' => '80'
        ]);

        $this->addColumn('shopping_portal', [
            'header' => $helper->__('Shopping portal'),
            'index' => 'shopping_portal',
            'width' => '80'
        ]);

        $this->addColumn('transaction_id', [
            'header' => $helper->__('Transaction Id'),
            'index' => 'transaction_id',
            'width' => '200'
        ]);

        $this->addColumn('transaction_tax', [
            'header' => $helper->__('Tax'),
            'index' => 'transaction_tax',
            'width' => '200'
        ]);

        $this->addColumn('transaction_shipping', [
            'header' => $helper->__('Shipping'),
            'index' => 'transaction_shipping',
            'width' => '200'
        ]);

        $this->addColumn('transaction_currency', [
            'header' => $helper->__('Transaction Currency'),
            'index' => 'transaction_currency',
            'width' => '200'
        ]);

        $this->addColumn('transaction_product_list_sku', [
            'header' => $helper->__('Produkt List'),
            'index' => 'transaction_product_list_sku',
            'width' => '200'
        ]);

        $this->addColumn('transaction_is_new_customer', [
            'header' => $helper->__('is new Customer'),
            'index' => 'transaction_is_new_customer',
            'width' => '200',
            'type' => 'options',
            'options' => [
                '1' => $helper->__('yes'),
                '0' => $helper->__('no'),
            ]
        ]);

        $this->addColumn('session_id', [
            'header' => $helper->__('Session Id'),
            'index' => 'session_id',
            'width' => '200'
        ]);

        $this->addColumn('update_time', [
            'header' => $helper->__('Update Time'),
            'index' => 'update_time',
            'type' => 'datetime',
            'width' => '150'
        ]);

        $this->addColumn('created_time', [
            'header' => $helper->__('Created Time'),
            'index' => 'created_time',
            'type' => 'datetime',
            'width' => '150'
        ]);

        $this->addExportType(
            '*/*/exportCsv',
            $this->__('CSV')
        );

        $this->addExportType(
            '*/*/exportExcel',
            $this->__('Excel XML')
        );

        return parent::_prepareColumns();
    }


    /**
     * @param Grafikentwicklung_Tracking_Model_Salestracking $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }


    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $modelPk = Mage::getModel('grafikentwicklung_tracking/salestracking')->getResource()->getIdFieldName();
        $this->setMassactionIdField($modelPk);
        $this->getMassactionBlock()->setFormFieldName('ids');

        // $this->getMassactionBlock()->setUseSelectAll(false);
        $this->getMassactionBlock()->addItem('delete', [
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
        ]);
        return $this;
    }
}
