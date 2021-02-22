<?php


namespace dcms\update\includes;

use dcms\update\libs\SimpleXLSX;


class Readfile{

    private $xlsx;
    private $path_file;

    public function __construct($path_file = ''){
        $options = get_option( 'dcms_usexcel_options' );

        $this->path_file = empty($path_file) ? $options['dcms_usexcel_input_file'] : $path_file;
        $this->sheet_number = $options['dcms_usexcel_sheet_field'];

        if ($this->file_exists() ){
            $this->xlsx = new SimpleXLSX($this->path_file);
        }
    }

    // File exits
    public function file_exists(){
        return file_exists( $this->path_file );
    }


    // Get data from file as array
    public function get_data_from_file(){
        $data = false;
        $data = $this->xlsx->rows($this->sheet_number);

        return $data;
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

    // Get ids by column name in an array, column -1 if not exits
    public function get_headers_ids(){

        $headers = $this->get_header($this->sheet_number);
        $options = get_option( 'dcms_usexcel_options' );

        $text_sku       = $options['dcms_usexcel_sku_field'];
        $text_stock     = $options['dcms_usexcel_stock_field'];
        $text_price     = $options['dcms_usexcel_price_field'];

        $headers_id             = [];

        $found = array_search($text_sku, $headers);
        $headers_id['sku'] =  ( ! empty($text_sku) && $found !== false ) ? $found : -1;

        $found = array_search($text_stock, $headers);
        $headers_id['stock'] =  ( ! empty($text_stock) && $found !== false ) ? $found : -1;

        $found = array_search($text_price, $headers);
        $headers_id['price'] = ( ! empty($text_price) && $found !== false ) ? $found : -1;

        return $headers_id;
    }

    // Is update file, validate if the file has changed
    public function file_has_changed(){
        $modified_bd = get_option('dcms_last_modified_file',0);
        $modified_file = filemtime($this->path_file);

        if ( $modified_file > $modified_bd ){
            return $modified_file;
        }
        return false;
    }


}

