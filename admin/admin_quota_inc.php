<?php
// $Header$
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

// is this used?
//if( isset( $_REQUEST["quotaset"] ) && isset( $_REQUEST["homeSample"] ) ) {
//	$gBitSystem->storeConfig( "home_quota", $_REQUEST["homeSample"], QUOTA_PKG_NAME );
//	$gBitSmarty->assign( 'home_quota', $_REQUEST["homeSample"] );
//}

require_once( QUOTA_PKG_CLASS_PATH.'LibertyQuota.php' );

if( !empty( $_REQUEST['cancelquota'] ) ) {
	unset( $_REQUEST['quota_id'] );
}

$gQuota = new LibertyQuota( !empty( $_REQUEST['quota_id'] ) ? $_REQUEST['quota_id'] : NULL );

if( !empty( $_REQUEST['savequota'] ) ) {
	if( $gQuota->store( $_REQUEST ) ) {
		header( 'Location: '.KERNEL_PKG_URL.'admin/index.php?page=quota' );
		die;
	} else {
		$saveError = TRUE;
		$gBitSmarty->assignByRef( 'errors', $gQuota->mErrors );
	}
} elseif( !empty( $_REQUEST['assignquota'] ) ) {
	foreach( array_keys( $_REQUEST ) as $key ) {
		if( preg_match( '/^quota_group_([-0-9]*)/', $key, $match ) ) {
			$groupId = $match[1];
			$gQuota->assignQuotaToGroup( $_REQUEST[$key], $groupId );
//vd( $match );
		}
	}
}
$gQuota->load();
if( $gQuota->isValid() || isset( $_REQUEST['newquota'] ) || !empty( $saveError ) ) {
	$gBitSmarty->assignByRef('gQuota', $gQuota);
} else {
	$quotas = $gQuota->getList();
	$systemGroups = $gQuota->getQuotaGroups();
	$gBitSmarty->assignByRef('systemGroups', $systemGroups );
foreach( array_keys( $systemGroups ) as $groupId ) {
	$groupQuota[$groupId] = $gQuota->getQuotaMenu( 'quota_group_'.$groupId, $systemGroups[$groupId]['quota_id'] );
}
	$gBitSmarty->assignByRef('groupQuota', $groupQuota );
	$gBitSmarty->assignByRef('quotaList', $quotas);
}

?>
