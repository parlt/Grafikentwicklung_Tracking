<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_Adminhtml_Emptycarttracking
 */
class Grafikentwicklung_Tracking_Block_Adminhtml_Emptycarttracking extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'grafikentwicklung_tracking';
        $this->_controller = 'adminhtml_emptycarttracking';
        $this->_headerText = Mage::helper('grafikentwicklung_tracking')->__('Empty Cart Tracking');

        parent::__construct();
        $this->_removeButton('add');
    }

}

