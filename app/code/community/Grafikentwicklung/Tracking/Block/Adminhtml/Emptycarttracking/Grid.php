<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Adminhtml_Emptycarttracking_Grid
 */
class Grafikentwicklung_Tracking_Block_Adminhtml_Emptycarttracking_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        /** @var Grafikentwicklung_Tracking_Model_Resource_Emptycarttracking_Collection|Varien_Data_Collection $collection */
        $collection = Mage::getModel('grafikentwicklung_tracking/emptycarttracking')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }


    /**
     * @return Mage_Adminhtml_Block_Widget_Grid|Grafikentwicklung_Tracking_Block_Adminhtml_Emptycarttracking_Grid
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

        $this->addColumn('is_empty_cart', [
            'header' => $helper->__('Is empty cart'),
            'index' => 'is_empty_cart',
            'width' => '10',
            'type' => 'options',
            'options' => [
                '1' => $helper->__('yes'),
                '0' => $helper->__('no'),
            ]
        ]);

        $this->addColumn('is_accessing_cart', [
            'header' => $helper->__('Is accessing cart'),
            'index' => 'is_accessing_cart',
            'width' => '10',
            'type' => 'options',
            'options' => [
                '1' => $helper->__('yes'),
                '0' => $helper->__('no'),
            ]
        ]);

        $this->addColumn('store_code', [
            'header' => $helper->__('Store'),
            'index' => 'store_code',
            'width' => '80'
        ]);

        $this->addColumn('session_id', [
            'header' => $helper->__('Session id'),
            'index' => 'session_id',
            'width' => '200'
        ]);

        $this->addColumn('session_name', [
            'header' => $helper->__('Session name'),
            'index' => 'session_name',
            'width' => '80'
        ]);

        $this->addColumn('cookie_domain', [
            'header' => $helper->__('Cookie domain'),
            'index' => 'cookie_domain',
            'width' => '100'
        ]);

        $this->addColumn('cookie_lifetime', [
            'header' => $helper->__('Cookie lifetime'),
            'index' => 'cookie_lifetime',
            'width' => '30'
        ]);


        $this->addColumn('session_remote_address', [
            'header' => $helper->__('Session remote address'),
            'index' => 'session_remote_address',
            'width' => '80'
        ]);

        $this->addColumn('session_http_user_agent', [
            'header' => $helper->__('Session http_user_agent'),
            'index' => 'session_http_user_agent',
            'width' => '80'
        ]);

        $this->addColumn('session_requested_urls', [
            'header' => $helper->__('Session Requested Urls'),
            'index' => 'session_requested_urls',
            'width' => '200',
            'renderer' => 'Grafikentwicklung_Tracking_Renderer_Array'
        ]);


        $this->addColumn('session_validator_data', [
            'header' => $helper->__('Session validator data'),
            'index' => 'session_validator_data',
            'width' => '200',
            'renderer' => 'Grafikentwicklung_Tracking_Renderer_Array'
        ]);

        $this->addColumn('session_http_via', [
            'header' => $helper->__('Session http_via'),
            'index' => 'session_http_via',
            'width' => '80'
        ]);

        $this->addColumn('session_http_x_forwarded_for', [
            'header' => $helper->__('Session http_x_forwarded_for'),
            'index' => 'session_http_x_forwarded_for',
            'width' => '80'
        ]);

        $this->addColumn('session_messages_collection', [
            'header' => $helper->__('Session messages_collection'),
            'index' => 'session_messages_collection',
            'width' => '80'
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
     * @param Grafikentwicklung_Tracking_Model_Emptycarttracking $row
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
