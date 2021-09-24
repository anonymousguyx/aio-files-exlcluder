<?php
/*
* Plugin Name: Folder Excluder for AIO WP Migration
* Description: This tiny, open-source plugin provides a missing functionality of All-in-One WP Migration plugin to exclude the selective folders from the backup. It will help keeping the backup file smaller and free from garbage data. Extra folders like Updraft, BackitUp, aiowm-backups, and Backups etc.
* Version: 3.0
* Plugin URI: https://github.com/anonymousguyx/ai1wpm-excluder/
* Author: Zeeshan Ahmed
* Author URI: https://www.upwork.com/o/profiles/users/~013f747c0979c28c0c/
* Requires at least: 3.0.1
* Tested up to: 5.7
* Requires PHP: 5.4
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*/

// Settings Menu
add_action('admin_menu', 'ai1excludes_menu');
function ai1excludes_menu(){
	add_submenu_page('options-general.php', 'AIO WP Migration Folders Excluder', 'AIO WP Migration Folders Excluder', 'administrator', 'ai1excludes', 'ai1excludes_menu_page', 'dashicons-lightbulb', '77' );
}

add_action( 'admin_init', 'a1i_options' );
function a1i_options(){
	register_setting( 'ai1exclude-data', 'folderpathx' );
}

function ai1excludes_menu_page(){ ?>
		<div class="wrap a1i-x">
			<h1 class="ai1title"> AIO WP Migration Folders Excluder </h1>
			<form class="ai1data" action="options.php" method="post">
			<?php settings_fields( 'ai1exclude-data' ); ?>
			<?php do_settings_sections( 'ai1excludes_menu' ); ?>
			<h3> Folder(s) name to exclude: </h3>
			<p class="ai1para">Please add the name of folder(s) that you want to exlude. Entries are folder paths relative to <span style="font-family:monospace">/wp-content/</span>, e.g. <span style="font-family:monospace">updraft,wp-backup,uploads/backupbuddy_backups</span></p>
			<p class="ai1para" style="color: #e74c3c;"><b>Note: Please do not add any space before or after comma.</b></p>
			<input type="text" name="folderpathx" value="<?php echo esc_attr( get_option( 'folderpathx' )); ?>">
			<?php submit_button(); ?>
			</form>
		</div>
		<?php

		if (class_exists('Ai1wm_Main_Controller')) {
			echo "<div class='wrap a1i-x colored'><div class='inner-wrap'>";
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
			echo "</div><p class='author-cr'>Made with ♥️ by <a href='https://www.upwork.com/o/profiles/users/~013f747c0979c28c0c/' target='_blank'>Zeeshan Ahmed</a></p></div></div>";
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
