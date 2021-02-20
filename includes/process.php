<?php

namespace dcms\update\includes;

use dcms\update\includes\Database;

class Process{
    public function __construct(){
        add_action( 'admin_post_process_form', [$this, 'dcms_process_force_update'] );
    }

    public function dcms_process_force_update(){
        wp_redirect( admin_url('tools.php?page=update-stock-excel&process=1') );
    }
}

