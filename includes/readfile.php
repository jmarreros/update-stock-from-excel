<?php


namespace dcms\update\includes;

use dcms\update\libs\SimpleXLSX;


class Readfile{

    private $xlsx;

    public function __construct($path_file){
        $this->xlsx = new SimpleXLSX($path_file);
    }

    // Get header file
    public function get_header(){
        $options = get_option( 'dcms_usexcel_options' ); // Get sheet number
        $sheet = $options['dcms_usexcel_sheet_field'];

        $rows = $this->xlsx->rows($sheet);

        if ( ! isset($rows[0]) ) return false;

        return $rows[0];
    }

}

