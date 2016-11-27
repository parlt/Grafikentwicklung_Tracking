<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */

/**
 * Class Grafikentwicklung_Tracking_Model_Resource_Emptycarttracking
 */
class Grafikentwicklung_Tracking_Model_Resource_Emptycarttracking extends Grafikentwicklung_Tracking_Model_Resource_Abstract
{

    protected function _construct()
    {
        $this->_init('grafikentwicklung_tracking/emptycarttracking', 'id');
    }


    /**
     * @param Grafikentwicklung_Tracking_Model_Emptycarttracking $object
     * @param string $sessionId
     * @param string $storeCode
     * @param string $remoteAddress
     * @param string $userAgent
     * @return $this
     */
    public function loadBySessionAndStoreData(
        Grafikentwicklung_Tracking_Model_Emptycarttracking $object,
        $sessionId, $storeCode, $remoteAddress, $userAgent)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getMainTable())
            ->where('session_id = ?', $sessionId)
            ->where('store_code = ?', $storeCode)
            ->where('session_remote_address = ?', $remoteAddress)
            ->where('session_http_user_agent = ?', $userAgent);

        $modelId = $adapter->fetchOne($select);
        if ($modelId) {
            $this->load($object, $modelId);
        } else {
            $object->setData([]);
        }

        return $this;
    }

}