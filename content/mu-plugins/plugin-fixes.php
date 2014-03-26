<?php

/**
 * Domain Mapping plugin applies an incorrect site_url value
 */

add_filter( 'pre_option_siteurl', function( $value ) {

	if ( $value && false === stripos( $value, '/wp' ) && function_exists( 'get_original_url' ) ) {
		$value = get_original_url( "siteurl" );
	}
	return $value;
}, 99 );

remove_filter( 'admin_url', 'domain_mapping_adminurl', 10, 3 );
