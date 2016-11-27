<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Adminhtml_SalestrackingController
 */
class Grafikentwicklung_Tracking_Adminhtml_SalestrackingController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Sales tracking'));
        $this->loadLayout();
        $this->_setActiveMenu('catalog/tracking');

        $layout = $this->getLayout();
        $block = $layout->createBlock('grafikentwicklung_tracking/adminhtml_salestracking');

        $this->_addContent($block);
        $this->renderLayout();
    }


    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('grafikentwicklung_tracking/adminhtml_salestracking_grid')->toHtml()
        );
    }




    public function editAction()
    {
        $salesTracking = Mage::getModel('grafikentwicklung_tracking/salestracking');
        $salesTrackingId = $this->getRequest()->getParam('id', false);

        if ($salesTrackingId !== false) {
            $salesTracking->load($salesTrackingId);
        }

        $block = $this->getLayout()->createBlock('grafikentwicklung_tracking/adminhtml_salestracking_edit');

        Mage::register('current_salestracking', $salesTracking);
        $this->loadLayout()
            ->_addContent($block)
            ->renderLayout();
    }





    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function deleteAction()
    {
        $salesTracking = Mage::getModel('grafikentwicklung_tracking/salestracking');
        $salesTrackingId = $this->getRequest()->getParam('id', false);

        if ($salesTrackingId) {
            $salesTracking->load($salesTrackingId);
        }

        try {
            $salesTracking->delete();

            $this->_getSession()->addSuccess(
                $this->__('The sales tracking has been deleted.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect('adminhtml/salestracking/index');
    }





    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function massDeleteAction()
    {
        $salesTrackingIds = $this->getRequest()->getParam('ids', false);

        $hasException = false;

        if ($salesTrackingIds !== false && is_array($salesTrackingIds)) {
            foreach ($salesTrackingIds as $salesTrackingId) {
                $salesTracking = Mage::getModel('grafikentwicklung_tracking/salestracking');
                if ($salesTrackingId) {
                    $salesTracking->load($salesTrackingId);
                }

                try {
                    $salesTracking->delete();

                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        if ($hasException === false) {
            $this->_getSession()->addSuccess(
                $this->__('The sales trackings has been deleted.')
            );
        }

        return $this->_redirect('adminhtml/salestracking/index');
    }


    public function exportCsvAction()
    {
        $fileName = 'salestracking.csv';

        /** @var Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking_Grid $grid */
        $grid = $this->getLayout()->createBlock('grafikentwicklung_tracking/adminhtml_salestracking_grid');

        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }


    public function exportExcelAction()
    {
        $fileName = 'salestracking.xls';

        /** @var Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking_Grid $grid */
        $grid = $this->getLayout()->createBlock('grafikentwicklung_tracking/adminhtml_salestracking_grid');

        $this->_prepareDownloadResponse($fileName, $grid->getExcel($fileName));
    }
}