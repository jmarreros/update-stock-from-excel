<?php

namespace dcms\update\includes;

class Database{
    private $wpdb;
    private $table_name;

    public function __construct(){
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->table_name = $this->wpdb->prefix . 'dcms_update_stock';
    }

    // Init activation create table
    public function create_table(){
        $sql = "DROP TABLE IF EXISTS {$this->table_name};
                CREATE TABLE {$this->table_name} (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `sku` varchar(50) COLLATE {$this->wpdb->collate} NOT NULL DEFAULT '',
                    `stock` int(10) unsigned NOT NULL DEFAULT '0',
                    `price` decimal(6,2) NOT NULL DEFAULT '0.00',
                    `date_update` datetime DEFAULT NULL,
                    `date_file` int(10) unsigned NOT NULL DEFAULT '0',
                    `updated` tinyint(1) DEFAULT '0',
                    PRIMARY KEY (`id`)
          ) COLLATE={$this->wpdb->collate};
        ";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }
}


// CREATE TABLE IF NOT EXISTS