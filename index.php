<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_quota/index.php,v 1.1.1.1.2.2 2005/07/26 15:50:25 drewslater Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: index.php,v 1.1.1.1.2.2 2005/07/26 15:50:25 drewslater Exp $
 * @package quota
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'quota' );

require_once( QUOTA_PKG_PATH.'LibertyQuota.php' );

$quota = new LibertyQuota();
$diskUsage = $quota->getUserUsage( $gBitUser->mUserId );
$diskQuota = $quota->getUserQuota( $gBitUser->mUserId );

if( $diskQuota != 0 ) {
	$quotaPercent = round( (($diskUsage / $diskQuota) * 100), 0 );
} else {
	$quotaPercent = 0;
}

if( $quotaPercent > 100 ) {
	$errors['disk_quota'] = "You are over your disk quota.";
	$gBitSmarty->assign_by_ref( 'errors', $errors );
	$quotaPercent = 100;
}

$gBitSmarty->assign( 'usage', round( ($diskUsage / 1000000), 2 ) );
$gBitSmarty->assign( 'quota', round( ($diskQuota / 1000000), 2 ) );
$gBitSmarty->assign_by_ref( 'quotaPercent', $quotaPercent );

$gBitSystem->display( 'bitpackage:quota/quota.tpl', 'View Quota' );

?>
