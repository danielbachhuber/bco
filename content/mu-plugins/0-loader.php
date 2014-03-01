<?php

$mu_plugins = array(
	'wordpress-importer/wordpress-importer.php'
	);
foreach( $mu_plugins as $mu_plugin ) {
	require_once dirname( __FILE__ ) . '/' . $mu_plugin;
}