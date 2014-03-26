<?php

/**
 * Domain Mapping plugin applies an incorrect site_url value
 */

add_filter( 'pre_option_siteurl', function( $value ) {

	if ( $value && defined( 'DOMAIN_MAPPING' ) ) {
		$value .= '/wp';
	}

	return $value;
}, 99 );

remove_filter( 'admin_url', 'domain_mapping_adminurl', 10, 3 );
