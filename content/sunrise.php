<?php
/**
 * Cribbed from the domain mapping plugin
 */

if ( ! defined( 'SUNRISE_LOADED' ) ) {
	define( 'SUNRISE_LOADED', 1 );
}

/**
 * Poor man's domain mapping table!
 */
$mapped_domains = array(
	// Development
	'danielbachhuber.dev'       => 'daniel.bachhuber.dev',
	// Production
	'danielbachhuber.com'       => 'daniel.bachhuber.co',
	'leahbachhuber.com'         => 'leah.bachhuber.co',
	);

/**
 * Check if the requested domain is an actual domain
 */
if ( isset( $mapped_domains[ $_SERVER['HTTP_HOST'] ] ) ) {

	$real_domain = $mapped_domains[ $_SERVER['HTTP_HOST'] ];

	$wpdb->suppress_errors();
	$mapped_blog = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->blogs} WHERE domain = %s LIMIT 0,1", $real_domain ) );
	$wpdb->suppress_errors( false );

}

/**
 * We need to domain map!
 */
if ( ! empty( $mapped_blog[0] ) ) {

	/**
	 * Admin requests need to happen on the mapped domain
	 */
	if ( is_admin() ) {
		header( 'Location: http://' . $real_domain . $_SERVER['REQUEST_URI'] );
		exit;
	}

	/**
	 * Set important globals
	 */
	$current_blog = $mapped_blog[0];
	$blog_id = $current_blog->blog_id;
	$site_id = $current_blog->site_id;
	$current_site = $wpdb->get_row( $wpdb->prepare( "SELECT * from {$wpdb->blogs} WHERE site_id = %d LIMIT 0,1", $current_blog->site_id ) );

	/**
	 * I guess we be using these constants
	 */
	define( 'COOKIE_DOMAIN', $_SERVER[ 'HTTP_HOST' ] );
	define( 'DOMAIN_MAPPING', 1 );
}

/**
 * Force mapped domain on the frontend
 */
if ( ! is_admin() && false !== ( $key = array_search( $_SERVER['HTTP_HOST'], $mapped_domains ) ) ) {

	if ( $key != $_SERVER['HTTP_HOST'] ) {
		header( 'Location: http://' . $key . $_SERVER['REQUEST_URI'] );
		exit;
	}

}

