<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking_Edit
 */
class Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'grafikentwicklung_tracking';
        $this->_controller = 'adminhtml_salestracking';
        $this->_mode = 'edit';

        $this->_removeButton('save');
        $this->_removeButton('saveandcontinue');
        $this->_removeButton('reset');

    }


    /**
     * @return Grafikentwicklung_Tracking_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('grafikentwicklung_tracking');
    }


    /**
     * @return Grafikentwicklung_Tracking_Model_Salestracking|null
     */
    protected function _getModel()
    {
        return Mage::registry('current_salestracking');
    }


    /**
     * @return string
     */
    protected function _getModelTitle()
    {
        return 'Salestracking';
    }


    /**
     * @return string
     */
    public function getHeaderText()
    {
        $model = $this->_getModel();
        $modelTitle = $this->_getModelTitle();
        if ($model && $model->getId() !== null) {
            return $this->_getHelper()->__("Edit $modelTitle (ID: {$model->getId()})");
        } else {
            return $this->_getHelper()->__("New $modelTitle");
        }
    }


    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/index');
    }


    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', array($this->_objectId => $this->getRequest()->getParam($this->_objectId)));
    }


}
