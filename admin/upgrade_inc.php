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

// query: create a quota_id_seq and bring the table up to date with the current max quota_id used in the quotas table - this basically for mysql
array( 'PHP' => '
	$query = $gBitDb->getOne("SELECT MAX(quota_id) FROM `'.BIT_DB_PREFIX.'quotas`");
	$tempId = $gBitDb->mDb->GenID("`'.BIT_DB_PREFIX.'quota_id_seq`", $query);
' ),
		)
	),

);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( QUOTA_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}
?>
