<?php
/*
* Plugin Name: All-in-One WP Migration Folders Excluder
* Description: This plugin provides functionality to exclude extra backup folders like Updraft, WpBackup etc from been backed up in All in One WP Migration backup. This will speed up the backup process and reduce the size of the backup.
* Version: 1.0
* Plugin URI: https://github.com/anonymousguyx/ai1wpm-excluder/
* Author: Zeeshan Ahmed
* Author URI: http://www.fiverr.com/zeeshanx
*
*/

// Settings Menu
add_action('admin_menu', 'ai1excludes_menu');
function ai1excludes_menu(){
	add_menu_page( 'All-in-One Excludes', 'All-in-One Excludes', 'administrator', 'ai1excludes', 'ai1excludes_menu_page', 'dashicons-image-filter', '77' );
}

add_action( 'admin_init', 'a1i_options' );
function a1i_options(){
	register_setting( 'ai1exclude-data', 'folderpathx' );
}

function ai1excludes_menu_page(){ ?>
		<div class="wrap">
			<h1 class="ai1title"> All-in-One WP Migration Folders Excluder </h1>
			<form class="ai1data" action="options.php" method="post">
			<?php settings_fields( 'ai1exclude-data' ); ?>
			<?php do_settings_sections( 'ai1excludes_menu' ); ?>
			<h3> Folder(s) Name to exclude: </h3>
			<p class="ai1para">Please specify the name of folders for example "updraft" (without quote). If have more than one folder, add a comma before adding another folder name.</p>
			<p class="ai1para" style="color: #e74c3c;"><b>Note: Do not add a space before or after comma.</b></p>
			<input type="text" name="folderpathx" value="<?php echo esc_attr( get_option( 'folderpathx' )); ?>">
			<?php submit_button(); ?>
			</form>
		</div>
		<?php

		if (class_exists('Ai1wm_Main_Controller')) {
			echo "<div class='wrap'><div class='inner-wrap'>";
			echo "<div class='folders-list'>";
			echo "<h3>Excluded folders list 🗂:</h3>";
			$string = esc_attr( get_option( 'folderpathx' ));
			$array = explode(',', $string);
			foreach ($array as $line) {
				echo $line;
				echo "<br>";
			};
			echo "</div></div></div>";
		} else {
			echo "<center>All-in-One WP Migration is not active. Please install/activate it to get this plugin working.</center>";
		}
}

// Load Styles
function load_styles(){
	wp_register_style( 'ai1styles', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', false, 'v.1.0' );
	wp_enqueue_style( 'ai1styles' );
}
add_action( 'admin_enqueue_scripts', 'load_styles' );

add_filter( 'ai1wm_exclude_content_from_export', function( $exclude ) {
		$string = esc_attr( get_option( 'folderpathx' ));
		$array = explode(',', $string);
		foreach ($array as $line) {
			$exclude[] = $line;
		};
		return $exclude;
} );
