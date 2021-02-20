<?php

namespace dcms\update\includes;

use dcms\update\includes\Database;
use dcms\update\includes\Readfile;

class Process{
    public function __construct(){
        add_action( 'admin_post_process_form', [$this, 'dcms_process_force_update'] );
    }

    public function dcms_process_force_update(){
        $file = new Readfile();
        $last_modified =  $file->file_has_changed();

        $data = $file->get_data_from_file();
        $headers_ids = $file->get_headers_ids();

        error_log(print_r($data,true));
        error_log(print_r($headers_ids,true));

        // TODO
        // Con la data y con el ID de las columnas se podrá insertar


        // $data = $file->get_data_from_file();

        // error_log(print_r($data,true));

        // Insert in database table
        // if ( $last_modified ){

        // }

        //update_option('dcms_last_modified_file', $modified_file );

        wp_redirect( admin_url('tools.php?page=update-stock-excel&process=1') );
    }



}


# TODO
// - Verificar fecha de actualización del archivo
// - Comparar la fecha de actualización con el registro de wp_options
// - Si es diferente entonces el archivo fue actualizado, realizar el vaciado de datos
// - Llenar la tabla de la BD
// - Llamar a la tarea de actualización

