<?php

namespace dcms\update\includes;

use dcms\update\includes\Database;
use dcms\update\includes\Readfile;

class Process{
    public function __construct(){
        add_action( 'admin_post_process_form', [$this, 'process_force_update'] );
    }

    public function process_force_update(){
        $file = new Readfile();

        // Validation
        if ( ! $file->file_exists() ) $this->exit_process(0);

        $last_modified =  $file->file_has_changed();

        // Validate if the file has changed, then insert into database
        if ( $last_modified >= get_option('dcms_last_modified_file') ){
            $this->rows_into_table($file, $last_modified);
            update_option('dcms_last_modified_file', $last_modified );
        }

        // update stock products
        $this->update_products();

        $this->exit_process();
    }

    // Exit process
    private function exit_process($process_ok = 1){
        wp_redirect( admin_url('tools.php?page=update-stock-excel&process='.$process_ok) );
        exit();
    }

    // Update products stock
    private function update_products(){
        $table = new Database();

        // Obtenemos los campos a filtrar
        $items = $table->select_table_filter();

        foreach ($items as $item) {

            // Get the product object
            $product = wc_get_product( $item->post_id );

            // Validate only simple products
            if ( $product->get_type() == 'simple'){
                $price = $product->get_price();
                $stock = $product->get_stock_quantity();

                // If price has changed
                if ( ! is_null($item->price) && $price !== $item->price){
                    $this->update_product_price($product, $item->price);
                }

                // If stock has changed
                if ( $stock !== $item->stock ){
                    wc_update_product_stock($product, $item->stock);
                }

                // Update table log
                $table->update_table($item->id);
            }

        }
    }

    // Update price of a specific product object
    private function update_product_price($product, $new_price){
        $product->set_regular_price($new_price);
        $product->set_sale_price($new_price);
        $product->set_price($new_price);
        $product->save();
    }


    // Insert data rows into table
    private function rows_into_table($file, $last_modified){

        $data = $file->get_data_from_file();

        // Validate get data from file
        if ( ! $data ) return false;

        $headers_ids = $file->get_headers_ids();

        // Validate required columns
        if ( $headers_ids['sku'] <= 0 || $headers_ids['stock'] <= 0 ){
            return false;
        }

        $table = new Database();
        foreach ($data as $key => $item) {
            if ( $key == 0 ) continue; // Exclude first line

            $row = [];
            if ( $item[$headers_ids['sku']] ){

                $row['date_file'] =  $last_modified;
                $row['sku']     = $item[$headers_ids['sku']];
                $row['stock']   = $item[$headers_ids['stock']];

                if ( $headers_ids['price'] >= 0 ){
                    $row['price']   = $item[$headers_ids['price']];
                }

                $table->insert_data($row);
            }
        }

    }

}