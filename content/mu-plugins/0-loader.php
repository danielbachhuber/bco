<?php

$mu_plugins = array(
	'easy-mode-auto-deploy/easy-mode-auto-deploy.php',
	'wordpress-mu-domain-mapping/domain_mapping.php',
	'wordpress-importer/wordpress-importer.php'
	);
foreach( $mu_plugins as $mu_plugin ) {
	require_once dirname( __FILE__ ) . '/' . $mu_plugin;
}