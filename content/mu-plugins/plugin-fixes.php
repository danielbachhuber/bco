<?php

/**
 * Domain Mapping plugin applies an incorrect site_url value
 */
$domain_mapping_filters = array(
	'pre_option_siteurl',
	'theme_root_uri',
	'stylesheet_uri',
	'stylesheet_directory',
	'stylesheet_directory_uri',
	'template_directory',
	'template_directory_uri'
	);

$filter_domain_mapping_siteurl = function( $value ) {
	global $pagenow;
 
	static $urls;

	if ( ! isset( $urls ) ) {
		$urls = array();
	}

	if ( ! $value || false !== stripos( $value, '/wp/' ) || substr( $value, -3 ) == '/wp' ) {
		return $value;
	}

	if ( ! isset( $urls[ $value ] ) ) {
		$urls[ $value ] = array(
			'search'   => domain_mapping_siteurl( 'NA' ),
			);
		if ( is_admin() ) {
			$urls[ $value ][ 'replace' ] = site_url();
		} else {
			$urls[ $value ][ 'replace' ] = home_url( '/wp' );
		}
	}

	if ( ! empty( $urls[ $value ][ 'replace' ] ) ) {
		$value = str_replace( $urls[ $value ][ 'search' ], $urls[ $value ][ 'replace' ], $value );
	}

	return $value;
};

if ( defined( 'DOMAIN_MAPPING' ) ) {
	foreach( $domain_mapping_filters as $domain_mapping_filter ) {
		add_filter( $domain_mapping_filter, $filter_domain_mapping_siteurl, 99 );
	}
} else {
	remove_filter( 'admin_url', 'domain_mapping_adminurl', 10, 3 );
}