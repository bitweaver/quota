<?php
// $Header: /cvsroot/bitweaver/_bit_quota/admin/admin_quota_inc.php,v 1.1.1.1.2.1 2005/07/26 15:50:25 drewslater Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
if (isset($_REQUEST["quotaset"]) && isset($_REQUEST["homeSample"])) {
	$gBitSystem->storePreference("home_quota", $_REQUEST["homeSample"]);
	$gBitSmarty->assign('home_quota', $_REQUEST["homeSample"]);
}

require_once( QUOTA_PKG_PATH.'LibertyQuota.php' );

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
		$gBitSmarty->assign_by_ref( 'errors', $gQuota->mErrors );
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
	$gBitSmarty->assign_by_ref('gQuota', $gQuota);
} else {
	$quotas = $gQuota->getList();
	$systemGroups = $gQuota->getQuotaGroups();
	$gBitSmarty->assign_by_ref('systemGroups', $systemGroups );
foreach( array_keys( $systemGroups ) as $groupId ) {
	$groupQuota[$groupId] = $gQuota->getQuotaMenu( 'quota_group_'.$groupId, $systemGroups[$groupId]['quota_id'] );
}
	$gBitSmarty->assign_by_ref('groupQuota', $groupQuota );
	$gBitSmarty->assign_by_ref('quotaList', $quotas);
}

?>
