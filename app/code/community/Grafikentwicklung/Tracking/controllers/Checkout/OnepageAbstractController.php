<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        26.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */


/**
 * If u use some other checkout extensions you can add them by
 * checking is module enable -> require -> extend
 */
if (Mage::helper('core')->isModuleEnabled('IWD_Opc')) {
    require_once Mage::getModuleDir('controllers', 'IWD_Opc')  . DS . 'Checkout'. DS .  'OnepageController.php';

    /**
     * Class Grafikentwicklung_Tracking_Checkout_OnepageAbstractController
     */
    class Grafikentwicklung_Tracking_Checkout_OnepageAbstractController extends IWD_Opc_Checkout_OnepageController{}
}else {
    require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'OnepageController.php';

    /**
     * Class Grafikentwicklung_Tracking_Checkout_OnepageAbstractController
     */
    class Grafikentwicklung_Tracking_Checkout_OnepageAbstractController extends Mage_Checkout_OnepageController{}
}



