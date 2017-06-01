<?php
/**
* Plugin Name: WP No Comments
* Plugin URI: http://russellramey.me/wordpress/wp-no-comments
* Description: Remove/disable comment functions of Wordpress. Great option if you are using wordpress as a CMS and not as a blog platform. Also, help keep your site more secure by removing user injected data into the database.
* Version: 1.0
* Author: Russell Ramey
* Author URI: http://russellramey.me/
*/

// Disable support for comments and trackbacks in post types
add_action('admin_init', 'wpnc_post_types_support');
function wpnc_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}

// Close comments on the front-end
add_filter('comments_open', 'wpnc_comments_status', 20, 2);
add_filter('pings_open', 'wpnc_comments_status', 20, 2);
function wpnc_comments_status() {
	return false;
}

// Hide existing comments (if any)
add_filter('comments_array', 'wpnc_hide_existing_comments', 10, 2);
function wpnc_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}

// Remove comments page in menu
add_action('admin_menu', 'wpnc_admin_menu');
function wpnc_admin_menu() {
	remove_menu_page('edit-comments.php');
}

// Redirect any user trying to access comments page
add_action('admin_init', 'wpnc_admin_menu_redirect');
function wpnc_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}

// Remove comments metabox from dashboard
add_action('admin_init', 'wpnc_dashboard');
function wpnc_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}

// Remove comments links from admin bar
add_action( 'wp_before_admin_bar_render', 'wpnc_remove_admin_bar_links' );
function wpnc_remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}





