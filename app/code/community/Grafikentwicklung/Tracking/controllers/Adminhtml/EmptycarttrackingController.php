<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Adminhtml_EmptycarttrackingController
 */
class Grafikentwicklung_Tracking_Adminhtml_EmptycarttrackingController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Empty cart tracking tracking'));
        $this->loadLayout();
        $this->_setActiveMenu('catalog/tracking');

        $layout = $this->getLayout();
        $block = $layout->createBlock('grafikentwicklung_tracking/adminhtml_emptycarttracking');

        $this->_addContent($block);
        $this->renderLayout();
    }


    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('grafikentwicklung_tracking/adminhtml_emptycarttracking_grid')->toHtml()
        );
    }




    public function editAction()
    {
        $emptyCartTracking = Mage::getModel('grafikentwicklung_tracking/emptycarttracking');
        $emptyCartTrackingId = $this->getRequest()->getParam('id', false);

        if ($emptyCartTrackingId !== false) {
            $emptyCartTracking->load($emptyCartTrackingId);
        }

        $block = $this->getLayout()->createBlock('grafikentwicklung_tracking/adminhtml_emptycarttracking_edit');

        Mage::register('current_emptycarttracking', $emptyCartTracking);
        $this->loadLayout()
            ->_addContent($block)
            ->renderLayout();
    }





    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function deleteAction()
    {
        $emptyCartTracking = Mage::getModel('grafikentwicklung_tracking/emptycarttracking');
        $emptyCartTrackingId = $this->getRequest()->getParam('id', false);

        if ($emptyCartTrackingId) {
            $emptyCartTracking->load($emptyCartTrackingId);
        }

        try {
            $emptyCartTracking->delete();

            $this->_getSession()->addSuccess(
                $this->__('The empty cart tracking has been deleted.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect('adminhtml/emptycarttracking/index');
    }





    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function massDeleteAction()
    {
        $emptyCartTrackingIds = $this->getRequest()->getParam('ids', false);

        $hasException = false;

        if ($emptyCartTrackingIds !== false && is_array($emptyCartTrackingIds)) {
            foreach ($emptyCartTrackingIds as $emptyCartTrackingId) {
                $emptyCartTracking = Mage::getModel('grafikentwicklung_tracking/emptycarttracking');
                if ($emptyCartTrackingId) {
                    $emptyCartTracking->load($emptyCartTrackingId);
                }

                try {
                    $emptyCartTracking->delete();

                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        if ($hasException === false) {
            $this->_getSession()->addSuccess(
                $this->__('The empty cart trackings has been deleted.')
            );
        }

        return $this->_redirect('adminhtml/emptycarttracking/index');
    }


    public function exportCsvAction()
    {
        $fileName = 'emptycarttracking.csv';

        /** @var Grafikentwicklung_Tracking_Block_Adminhtml_Emptycarttracking_Grid $grid */
        $grid = $this->getLayout()->createBlock('grafikentwicklung_tracking/adminhtml_emptycarttracking_grid');

        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }


    public function exportExcelAction()
    {
        $fileName = 'emptycarttracking.xls';

        /** @var Grafikentwicklung_Tracking_Block_Adminhtml_Emptycarttracking_Grid $grid */
        $grid = $this->getLayout()->createBlock('grafikentwicklung_tracking/adminhtml_emptycarttracking_grid');

        $this->_prepareDownloadResponse($fileName, $grid->getExcel($fileName));
    }
}