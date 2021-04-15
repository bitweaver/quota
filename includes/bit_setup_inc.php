<?php
global $gBitSystem, $gBitSmarty;

$registerHash = array(
	'package_name' => 'quota',
	'package_path' => dirname( dirname( __FILE__ ) ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );
?>
