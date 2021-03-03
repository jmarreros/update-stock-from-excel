<?php

// Exit process
function exit_process($process_ok = 1, $redirection){
    if ( $redirection ) wp_redirect( admin_url( DCMS_SUBMENU . '&page=update-stock-excel&process='.$process_ok) );
    exit();
}

