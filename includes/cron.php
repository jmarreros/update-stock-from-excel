<?php

namespace dcms\update\includes;

use dcms\update\includes\Process;

class Cron{
    private $process;

    public function __construct(){
        add_filter( 'cron_schedules', [ $this, 'dcms_custom_schedule' ]);
        add_action( 'dcms_cron_hook', [ $this, 'dcms_cron_process' ] );
        $this->process = new Process();
    }

    // Add new schedule
    public function dcms_custom_schedule( $schedules ) {
        $schedules['dcms_interval'] = array(
            'interval' => DCMS_INTERVAL_SECONDS,
            'display' => DCMS_INTERVAL_SECONDS. ' seconds'
        );
        return $schedules;
    }

    // Cron process
    public function dcms_cron_process() {
        $options = get_option( 'dcms_usexcel_options' );
        $cron_enabled = isset( $options['dcms_usexcel_cron_field'] );

        if ( $cron_enabled ){
            $this->process->process_update();
        }
        error_log('Mi evento se ejecutó: '.Date("h:i:sa"));
    }
}