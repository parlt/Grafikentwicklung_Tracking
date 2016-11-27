<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking_Edit_Form
 */
class Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * @return Grafikentwicklung_Tracking_Model_Salestracking
     */
    protected function _getModel()
    {
        return Mage::registry('current_salestracking');
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
        return 'Salestracking';
    }


    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        /** @var  Grafikentwicklung_Tracking_Model_Salestracking $model */
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

        $fieldSet->addField('store_code', 'text', array(
            'name' => 'store_code',
            'label' => $helper->__('Store'),
            'readonly' => true,
        ));

        $fieldSet->addField('shopping_portal', 'text', array(
            'name' => 'shopping_portal',
            'label' => $helper->__('Shopping portal'),
            'readonly' => true,
        ));

        $fieldSet->addField('transaction_id', 'text', array(
            'name' => 'transaction_id',
            'label' => $helper->__('Transaction Id'),
            'readonly' => true,
        ));

        $fieldSet->addField('transaction_tax', 'text', array(
            'name' => 'transaction_tax',
            'label' => $helper->__('Tax'),
            'readonly' => true,
        ));

        $fieldSet->addField('transaction_shipping', 'text', array(
            'name' => 'transaction_shipping',
            'label' => $helper->__('Shipping'),
            'readonly' => true,
        ));

        $fieldSet->addField('transaction_currency', 'text', array(
            'name' => 'transaction_currency',
            'label' => $helper->__('Transaction Currency'),
            'readonly' => true,
        ));

        $fieldSet->addField('transaction_product_list_sku', 'textarea', array(
            'name' => 'transaction_product_list_sku',
            'label' => $helper->__('Produkt List'),
            'readonly' => true,
        ));

        $fieldSet->addField('transaction_is_new_customer', 'select', array(
            'name' => 'transaction_is_new_customer',
            'label' => $helper->__('is new Customer'),
            'readonly' => true,
            'options' => [
                '1' => $helper->__('yes'),
                '0' => $helper->__('no'),
            ]
        ));

        $fieldSet->addField('session_id', 'text', array(
            'name' => 'session_id',
            'label' => $helper->__('Session Id'),
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
