<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking
 */
class Grafikentwicklung_Tracking_Block_Adminhtml_Salestracking extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'grafikentwicklung_tracking';
        $this->_controller = 'adminhtml_salestracking';
        $this->_headerText = Mage::helper('grafikentwicklung_tracking')->__('Sales Tracking by Portal');

        parent::__construct();
        $this->_removeButton('add');

    }

}

