<?php

namespace dcms\update\includes;

class Database{
    public function create_table(){
        global $wpdb;

        $table_name = $wpdb->prefix . 'dcms_update_stock';
    }
}

// DROP TABLE IF EXISTS `wp_dcms_update_stock`;
// CREATE TABLE `wp_dcms_update_stock` (
//     `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
//     `stock` int(10) unsigned NOT NULL DEFAULT '0',
//     `price` decimal(6,2) NOT NULL DEFAULT '0.00',
//     `date_update` datetime DEFAULT NULL,
//     `date_file` int(10) unsigned NOT NULL DEFAULT '0',
//     PRIMARY KEY (`id`)
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
