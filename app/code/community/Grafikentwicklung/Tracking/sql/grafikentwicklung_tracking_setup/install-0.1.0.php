<?php
/**
 * @category    Grafikentwicklung
 * @package     Tracking
 * @author      Grafik, Entwicklung & IT Service <info@pierrearlt.com>
 * @date        21.11.2016
 * @license     https://github.com/parlt/Grafikentwicklung_Tracking/blob/master/LICENSE The MIT License (MIT)
 */


/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$tableSalesTracking = $this->getTable('grafikentwicklung_tracking/salestracking');
$emptyCartTracking = $this->getTable('grafikentwicklung_tracking/emptycarttracking');
$installer->run("
-- DROP TABLE IF EXISTS {$tableSalesTracking};
CREATE TABLE IF NOT EXISTS `{$tableSalesTracking}` (
  `id` int(10) unsigned NOT NULL,
  `store_code` varchar(250) NOT NULL,
  `transaction_id` varchar(250) NOT NULL,
  `transaction_tax` decimal(14,2) NOT NULL,
  `transaction_total` decimal(14,2) NOT NULL,
  `transaction_shipping` decimal(14,2) NOT NULL,
  `transaction_currency` varchar(30) NOT NULL,
  `transaction_units_ordered` int(10) unsigned NOT NULL,
  `transaction_product_list_sku` varchar(2000) NOT NULL,
  `transaction_product_data` varchar(2000) NOT NULL,
  `transaction_is_new_customer` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `session_id` varchar(500) NOT NULL,
  `shopping_portal` varchar(500) NOT NULL DEFAULT 'default',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 ALTER TABLE {$tableSalesTracking} 
    ADD PRIMARY KEY(`id`);
    
 ALTER TABLE {$tableSalesTracking}
    MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
    
-- DROP TABLE IF EXISTS {$emptyCartTracking};

CREATE TABLE IF NOT EXISTS `{$emptyCartTracking}` (
  `id` int(10) unsigned NOT NULL,
  `is_empty_cart` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `is_accessing_cart` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `store_code` varchar(250) NOT NULL,
  `session_id` varchar(250) DEFAULT NULL,
  `session_name` varchar(250) DEFAULT NULL,
  `cookie_domain` varchar(100) DEFAULT NULL,
  `cookie_lifetime` varchar(100) DEFAULT NULL,
  `session_remote_address` varchar(100) DEFAULT NULL,
  `session_http_via` varchar(100) DEFAULT NULL,
  `session_http_x_forwarded_for` varchar(100) DEFAULT NULL,
  `session_http_user_agent` varchar(2000) DEFAULT NULL,
  `session_messages_collection` varchar(2000) DEFAULT NULL,
  `session_validator_data` varchar(2000) DEFAULT NULL,
  `session_requested_urls` varchar(2000) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
 ALTER TABLE {$emptyCartTracking} 
    ADD PRIMARY KEY(`id`);
    
 ALTER TABLE {$emptyCartTracking}
    MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
    ");

$installer->endSetup();