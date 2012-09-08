<?php
/*
Plugin Name: Suicide
Version: 2.0
Plugin URI: http://justinsomnia.org/2006/04/wordpress-suicide/
Description: Delete all content from your blog's database (by table). Goto <a href="tools.php?page=suicide">Tools &gt; Suicide</a> to operate.
Author: Justin Watt
Author URI: http://justinsomnia.org/

LICENSE

wp-suicide.php
Copyright (C) 2012 Justin Watt
justincwatt@gmail.com
http://justinsomnia.org/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

new Suicide();
class Suicide {
	
	/**
	 * List of tables available to suicide
	 * @var Array
	 */
	private $tables = array(
		'posts'              => array( 'checked' => true ),
		'comments'           => array( 'checked' => true ),
		'commentmeta'        => array( 'checked' => true ),
		'links'              => array( 'checked' => true ),
		'postmeta'           => array( 'checked' => true ),
		'term_relationships' => array( 'checked' => true ),
		'terms'              => array( 'checked' => true ),
		'term_taxonomy'      => array( 'checked' => true ),
		'users'              => array( 'checked' => false ),
		'usermeta'           => array( 'checked' => false ),
		'options'            => array( 'checked' => false )
	);
	
	public function __construct() {
		// Individual site suicide - Tools > Suicide
		add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );
		
		// Total network suicide - Network Admin > Sites > Network Suicide
		add_action( 'network_admin_menu', array( &$this, 'add_network_admin_menu') );
		
		// Let outsiders add more tables to our list
		apply_filters( 'suicide_tables', $this->tables );
	}

	/**
	 * Adds a new menu item under Tools > Suicide for individual site suicide
	 */
	public function add_admin_menu() {
		add_management_page( 'Commit Suicide', ' Suicide', 'manage_options', 'suicide', array( &$this, 'page_single_suicide' ) );
	}
	
	/**
	 * Show the page for the individual site suicide
	 * @global type $wpdb
	 */
	public function page_single_suicide() {
		global $wpdb;

		if ( isset( $_POST['function'] ) && $_POST['function'] == 'commit-suicide' ) {
			$this->do_suicide();
			$this->reveal_suicide();
		} else {
			require_once( 'suicide-form.php' );
		}
	}
	
	/**
	 * Adds a new menu item under Sites > Network Suicide for suicide of all network content
	 */
	public function add_network_admin_menu() {
		add_submenu_page( 'sites.php', 'Commit Network Suicide', 'Network Suicide', 'manage_options', 'suicide', array( &$this, 'page_network_suicide') );
	}
	
	/**
	 * Show the page for network suicide
	 * @global type $wpdb
	 */
	public function page_network_suicide() {
		global $wpdb;
		
		$blogs = $this->blog_list();
		
		if ( isset( $_POST['function'] ) && $_POST['function'] == 'commit-suicide' ) {
			if ( isset( $_POST['all_sites'] ) ) {
				$b = $this->blog_list();
			} else {
				$temp = array();
				foreach( $_POST['blogs'] AS $b ) {
					$temp[$b] = $blogs[$b];
				}
				$blogs = $temp;
			}
			
			// Remove each blog's contents
			foreach ( $blogs AS $blog_id => $domain ) {
				echo "<h1>$domain</h1>";
				$this->do_suicide( $blog_id );
				$this->reveal_suicide();
			}
		
		} else {
			require_once( 'network-suicide-form.php' );
		}
	}
	
	/**
	 * Grab a listing of all the blogs on this install in a usable/searchable format
	 * @global WPDB $wpdb
	 * @return Array
	 */
	private function blog_list() {
		global $wpdb;
		$blogs = $wpdb->get_results("
					SELECT blog_id, domain
					FROM {$wpdb->blogs}
					WHERE site_id = '{$wpdb->siteid}'
					AND spam = '0'
					AND deleted = '0'
					AND archived = '0'
					AND blog_id != 1
				");
		$sites = array();
		foreach ( $blogs AS $b ) {
			$sites[$b->blog_id] = $b->domain;
		}
		return $sites;
	}

	/**
	 * Show which tables were emptied
	 */
	private function reveal_suicide() {
		?>
		<div class='wrap'>
			<h2>Suicide Results</h2>
				<b>Records removed from:</b><ul>
					<?php
				foreach ( array_keys( $this->tables ) AS $table ) {
					if ( isset($_POST["delete_$table"] ) ) {
						echo "<li>$table</li>";
					}
				}
				?>
				</ul>
		</div>
		<?php
	}
	
	/**
	 * Perform the suicidal operation. If a blog ID is not passed in the current
	 * blog will be used
	 *
	 * @param Integer $blog_id
	 */
	private function do_suicide( $blog_id = null ) {
		global $wpdb;
	
		// Security check for valid user action
		if ( !check_admin_referer('commit-suicide') )
			return false;
		
		if ( !is_null( $blog_id ) )
			switch_to_blog ( $blog_id );

		// Loop through the set of tables to possibly empty.
		foreach ( array_keys( $this->tables ) AS $table ) {
			if ( isset( $_POST["delete_$table"] ) ) {
				$wpdb->query( "TRUNCATE TABLE {$wpdb->$table}" );
			}
		}
		
		if ( !is_null( $blog_id ) )
			restore_current_blog(); // defined in wp-includes/ms-blogs.php
		
		if ( isset( $_POST['prevent_further_suicide'] ) ) {
			$this->prevent_suicide();
		}
	}
	
	/**
	 * Deactivates the plugin. To use again the user must reactivate through
	 * the Plugins page
	 */
	private function prevent_suicide() {
		deactivate_plugins( __FILE__, true ); // Omitting 3rd option to only turn off on this site
	}
} // end class
