<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Adminhtml_Emptycarttracking_Edit_Form
 */
class Grafikentwicklung_Tracking_Block_Adminhtml_Emptycarttracking_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * @return Grafikentwicklung_Tracking_Model_Emptycarttracking
     */
    protected function _getModel()
    {
        return Mage::registry('current_emptycarttracking');
    }


    /**
     * @return Grafikentwicklung_Tracking_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('grafikentwicklung_tracking');
    }


    /**
     * @return string
     */
    protected function _getModelTitle()
    {
        return 'Empty cart tracking';
    }


    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        /** @var  Grafikentwicklung_Tracking_Model_Emptycarttracking $model */
        $model = $this->_getModel();

        $modelTitle = $this->_getModelTitle();

        /** @var Grafikentwicklung_Tracking_Helper_Data $helper */
        $helper = Mage::helper('grafikentwicklung_tracking');

        /** @var Varien_Data_Form $form */
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save'),
            'method' => 'post'
        ));

        /** @var Varien_Data_Form_Element_Fieldset $fieldSet */
        $fieldSet = $form->addFieldset('base_fieldset', array(
            'legend' => $this->_getHelper()->__("$modelTitle Information"),
            'class' => 'fieldset-wide',
        ));

        if ($model && $model->getId() !== null) {
            $modelPk = $model->getResource()->getIdFieldName();
            $fieldSet->addField($modelPk, 'hidden', array(
                'name' => $modelPk,
            ));
        }

        $fieldSet->addField('is_empty_cart', 'select', array(
            'name' => 'is_empty_cart',
            'label' => $helper->__('Is empty cart'),
            'readonly' => true,
            'options' => [
                '1' => $helper->__('yes'),
                '0' => $helper->__('no'),
            ]
        ));

        $fieldSet->addField('is_accessing_cart', 'select', array(
            'name' => 'is_accessing_cart',
            'label' => $helper->__('Is accessing cart'),
            'readonly' => true,
            'options' => [
                '1' => $helper->__('yes'),
                '0' => $helper->__('no'),
            ]
        ));

        $fieldSet->addField('session_id', 'text', array(
            'name' => 'session_id',
            'label' => $helper->__('Session id'),
            'readonly' => true,
        ));

        $fieldSet->addField('store_code', 'text', array(
            'name' => 'store_code',
            'label' => $helper->__('Store'),
            'readonly' => true,
        ));

        $fieldSet->addField('session_name', 'text', array(
            'name' => 'session_name',
            'label' => $helper->__('Session name'),
            'readonly' => true,
        ));

        $fieldSet->addField('cookie_domain', 'text', array(
            'name' => 'cookie_domain',
            'label' => $helper->__('Cookie domain'),
            'readonly' => true,
        ));

        $fieldSet->addField('cookie_lifetime', 'text', array(
            'name' => 'cookie_lifetime',
            'label' => $helper->__('Cookie lifetime'),
            'readonly' => true,
        ));

        $fieldSet->addField('session_remote_address', 'text', array(
            'name' => 'session_remote_address',
            'label' => $helper->__('Session remote address'),
            'readonly' => true,
        ));

        $fieldSet->addField('session_http_user_agent', 'text', array(
            'name' => 'session_http_user_agent',
            'label' => $helper->__('Session http_user_agent'),
            'readonly' => true,
        ));

        $fieldSet->addField('session_http_via', 'text', array(
            'name' => 'session_http_via',
            'label' => $helper->__('Session http_via'),
            'readonly' => true,
        ));

        $fieldSet->addField('session_http_x_forwarded_for', 'text', array(
            'name' => 'session_http_x_forwarded_for',
            'label' => $helper->__('Session http_x_forwarded_for'),
            'readonly' => true,
        ));

        $fieldSet->addField('session_messages_collection', 'text', array(
            'name' => 'session_messages_collection',
            'label' => $helper->__('Session messages collection'),
            'readonly' => true,
        ));

        $fieldSet->addField('session_validator_data', 'textarea', array(
            'name' => 'session_validator_data',
            'label' => $helper->__('Session validator data'),
            'readonly' => true,
        ));

        $fieldSet->addField('session_requested_urls', 'textarea', array(
            'name' => 'session_requested_urls',
            'label' => $helper->__('Session Requested Urls'),
            'readonly' => true,
        ));


        if ($model) {
            $data = $model->getDataForLogFile();
            $form->setValues($data);
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
