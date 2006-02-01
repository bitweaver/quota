<?php

global $gBitSystem, $gUpgradeFrom, $gUpgradeTo;

$upgrades = array(

	'BWR1' => array(
		'BWR2' => array(
// de-tikify tables
array( 'DATADICT' => array(
	array( 'RENAMETABLE' => array(
		'tiki_quotas' => 'quotas',
		'tiki_quotas_group_map' => 'quotas_group_map',
	)),
)),
		)
	),

);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( QUOTA_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}
?>
