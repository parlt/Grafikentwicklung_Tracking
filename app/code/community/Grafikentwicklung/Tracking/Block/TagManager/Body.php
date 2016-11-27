<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Block_TagManager_Body
 */
class Grafikentwicklung_Tracking_Block_TagManager_Body extends Grafikentwicklung_Tracking_Block_TagManager_Abstract
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('grafikentwicklung/tracking/tagmanger/body.phtml');
    }
}
