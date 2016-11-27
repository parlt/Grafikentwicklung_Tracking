<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Model_Resource_Salestracking
 */
class Grafikentwicklung_Tracking_Model_Resource_Salestracking extends Grafikentwicklung_Tracking_Model_Resource_Abstract
{

    protected function _construct()
    {
        $this->_init('grafikentwicklung_tracking/salestracking', 'id');
    }


    /**
     * @param Grafikentwicklung_Tracking_Model_Salestracking $object
     * @param string $sessionId
     * @return $this
     */
    public function loadBySessionId(Grafikentwicklung_Tracking_Model_Salestracking $object, $sessionId)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getMainTable())
            ->where('session_id = ?', $sessionId);
        $modelId = $adapter->fetchOne($select);
        if ($modelId) {
            $this->load($object, $modelId);
        } else {
            $object->setData([]);
        }

        return $this;
    }

}