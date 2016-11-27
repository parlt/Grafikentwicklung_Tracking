<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Renderer_Arrayrenderer
 */
class Grafikentwicklung_Tracking_Renderer_Array extends  Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return mixed
     */
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        $unserialized = unserialize($value);
        $data = json_decode($unserialized,true);
        $output = '<span>';
        foreach ($data as $key => $item){
            $output.= '<span>'.$key.'</span> : <span>'.$item.'</span></span> <br>';
        }
        $output .= '</span>';
        return $output;

    }
}
