<?php
/*
* Plugin Name: AIO Files Excluder
* Description: Provides functionality to exclude extra folders like Updraft, WpBackup etc from been backed up in All in One WP Migration backup tool. This will speed up the backup process and reduce the size of the backup.
* Version: 2.2
* Plugin URI: https://github.com/anonymousguyx/ai1wpm-excluder/
* Author: Zeeshan Ahmed
* Author URI: https://www.fiverr.com/zeeshanx
* Requires at least: 3.0.1
* Tested up to: 5.2
* Requires PHP: 5.2.4
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*/

// Settings Menu
add_action('admin_menu', 'ai1excludes_menu');
function ai1excludes_menu(){
	add_menu_page( 'AIO Exluded Files', 'AIO Exluded Files', 'administrator', 'ai1excludes', 'ai1excludes_menu_page', 'dashicons-lightbulb', '77' );
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
			<h3> Folder(s) name to exclude: </h3>
			<p class="ai1para">Please add the name of folder(s) that you want to exlude. Example "Updraft,WP-Backup" (without quote).</p>
			<p class="ai1para" style="color: #e74c3c;"><b>Note: Please do not add any space before or after comma.</b></p>
			<input type="text" name="folderpathx" value="<?php echo esc_attr( get_option( 'folderpathx' )); ?>">
			<?php submit_button(); ?>
			</form>
		</div>
		<?php

		if (class_exists('Ai1wm_Main_Controller')) {
			echo "<div class='wrap'><div class='inner-wrap'>";
			echo "<div class='folders-list'>";
			echo "<h3>Excluded folders list <span class='dashicons dashicons-external'></span>:</h3>";
			$string = esc_attr( get_option( 'folderpathx' ));
			$array = explode(',', $string);
			$fcount = 1;
			foreach ($array as $line) {
				echo $fcount; echo '. '; echo $line;
				echo "<br>";
				$fcount++;
			};
			echo "</div><p class='author-cr'>Made by <a href='https://www.fiverr.com/zeeshanx' target='_blank'>Zeeshanx</a></p></div></div>";
		} else {
			echo "<center>All-in-One WP Migration is not active. Please install/activate it to get this plugin working.</center>";
		}
}

// Load Styles
function aoi_ex_load_styles(){
	wp_register_style( 'ai1styles', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', false, 'v.2.0' );
	wp_enqueue_style( 'ai1styles' );
}
add_action( 'admin_enqueue_scripts', 'aoi_ex_load_styles' );

add_filter( 'ai1wm_exclude_content_from_export', function( $exclude ) {
		$string = esc_attr( get_option( 'folderpathx' ));
		$array = explode(',', $string);
		foreach ($array as $line) {
			$exclude[] = $line;
		};
		return $exclude;
} );
