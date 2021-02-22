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
        $last_modified =  $file->file_has_changed();


        // Validate if the file has changed, then insert into database
        // if ( $last_modified >= get_option('dcms_last_modified_file') ){
        //     $this->rows_into_table($file, $last_modified);
        //     update_option('dcms_last_modified_file', $last_modified );
        // }

        $this->update_stock_products();

        wp_redirect( admin_url('tools.php?page=update-stock-excel&process=1') );
        exit();
    }

    // Update products stock
    private function update_stock_products(){
        $table = new Database();

        $sql = $table->select_table_filter();
        error_log(print_r($sql,true));
    }


    // Insert data rows into table
    private function rows_into_table($file, $last_modified){

        $data = $file->get_data_from_file();
        $headers_ids = $file->get_headers_ids();

        $table = new Database();
        foreach ($data as $key => $item) {
            if ( $key == 0 ) continue; // Exclude first line

            $row = [];
            if ( $item[$headers_ids['sku']] ){

                $row['sku']     = $item[$headers_ids['sku']];
                $row['stock']   = $item[$headers_ids['stock']];
                $row['price']   = $item[$headers_ids['price']];
                $row['date_file'] =  $last_modified;

                $table->insert_data($row);
            }
        }

    }


}


# TODO
// - Verificar fecha de actualización del archivo
// - Comparar la fecha de actualización con el registro de wp_options
// - Si es diferente entonces el archivo fue actualizado, realizar el vaciado de datos
// - Llenar la tabla de la BD
// - Llamar a la tarea de actualización

