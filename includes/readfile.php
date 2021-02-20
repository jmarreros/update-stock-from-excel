<?php


namespace dcms\update\includes;

use dcms\update\libs\SimpleXLSX;


class Readfile{

    private $xlsx;
    private $path_file;

    public function __construct($path_file){
        $this->path_file = $path_file;
        $this->xlsx = new SimpleXLSX($path_file);
    }

    // Get header file
    public function get_header( $sheet_number = false){

        if ( $sheet_number === false ){
            $options = get_option( 'dcms_usexcel_options' ); // Get sheet number from database
            $sheet_number = $options['dcms_usexcel_sheet_field'];
        }

        $rows = $this->xlsx->rows($sheet_number);

        if ( ! isset($rows[0]) ) return false;

        return $rows[0];
    }

    // Is update file, validate if the file has changed
    public function file_has_changed(){
        $modified_bd = get_option('dcms_last_modified_file',0);
        $modified_file = filemtime($this->path_file);

        error_log(print_r($modified_bd,true));
        error_log(print_r($modified_file,true));


        if ( $modified_bd <> $modified_file ){
            update_option('dcms_last_modified_file', $modified_file );
            return true;
        }
        return false;
    }


}

