<?php
/*
Plugin Name: Update stock Excel
Plugin URI: https://decodecms.com
Description: Update WooCommerce stock from an excel file
Version: 1.0
Author: Jhon Marreros Guzmán
Author URI: https://decodecms.com
Text Domain: dcms-update-stock-excel
Domain Path: languages
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

namespace dcms\update;

use dcms\update\includes\Plugin;
use dcms\update\includes\Submenu;
use dcms\update\includes\Enqueue;
use dcms\update\includes\Configuration;
use dcms\update\includes\Readfile;
use dcms\update\includes\Process;
use dcms\update\includes\Cron;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin class to handle settings constants and loading files
**/
final class Loader{

	// Define all the constants we need
	public function define_constants(){
		define ('DCMS_UPDATE_VERSION', '1.0');
		define ('DCMS_UPDATE_PATH', plugin_dir_path( __FILE__ ));
		define ('DCMS_UPDATE_URL', plugin_dir_url( __FILE__ ));
		define ('DCMS_UPDATE_BASE_NAME', plugin_basename( __FILE__ ));
		define ('DCMS_COUNT_BATCH_PROCESS', 5); // Amount of registers to update every time
		define ('DCMS_INTERVAL_SECONDS', 20); // For cron taks
	}

	// Load all the files we need
	public function load_includes(){
		include_once ( DCMS_UPDATE_PATH . '/includes/plugin.php');
		include_once ( DCMS_UPDATE_PATH . '/includes/submenu.php');
		include_once ( DCMS_UPDATE_PATH . '/includes/enqueue.php');
		include_once ( DCMS_UPDATE_PATH . '/includes/configuration.php');
		include_once ( DCMS_UPDATE_PATH . '/includes/readfile.php');
		include_once ( DCMS_UPDATE_PATH . '/includes/database.php');
		include_once ( DCMS_UPDATE_PATH . '/includes/process.php');
		include_once ( DCMS_UPDATE_PATH . '/includes/cron.php');
		include_once ( DCMS_UPDATE_PATH . '/libs/simplexlsx.php');
	}

	// Load tex domain
	public function load_domain(){
		add_action('plugins_loaded', function(){
			$path_languages = dirname(DCMS_UPDATE_BASE_NAME).'/languages/';
			load_plugin_textdomain('dcms-update-stock-excel', false, $path_languages );
		});
	}

	// Add link to plugin list
	public function add_link_plugin(){
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ){
			return array_merge( array(
				'<a href="' . esc_url( admin_url( '/tools.php?page=update-stock-excel' ) ) . '">' . __( 'Settings', 'dcms-update-stock-excel' ) . '</a>'
			), $links );
		} );
	}

	// Initialize all
	public function init(){
		$this->define_constants();
		$this->load_includes();
		$this->load_domain();
		$this->add_link_plugin();
		new Plugin();
		new SubMenu();
		new Enqueue();
		new Configuration();
		new Process();
		new Cron();
	}

}

$dcms_update_process = new Loader();
$dcms_update_process->init();


#TODO
# - Comprobar si el archivo esta actualizado
# - Hacer la actualización manualmente
# - llenar todo en una tabla de BD
# - En la tabla debe haber una columna de concluído
# - Verificar fecha del archivo en caso cambie para hacer de nuevo el proceso
# - Configurar el cron de WordPress

/*

Default
=======

/Users/jmarreros/www/encurso/wordpress55/excel/INVENTARIO.xlsx
4
COD.PRO.
CANTIDAD
P.COMPRA
*/

