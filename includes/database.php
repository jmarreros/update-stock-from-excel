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

    // Insert data
    public function insert_data( $row ){
        return $this->wpdb->insert($this->table_name, $row);
    }

    // Read table with current lastmodified date file
    public function select_table( $last_modified = false ){

        if ( ! $last_modified ){
            $last_modified = get_option('dcms_last_modified_file');
        }

        $sql = "SELECT * FROM {$this->table_name} WHERE date_file = {$last_modified}";
        return $this->wpdb->get_results($sql);
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