<?php
/**
 * @version  $Revision$
 * @package  quota
 * 
 * settings that are useful to know about at upload time
 */

/**
 * quota setup
 */
require_once( QUOTA_PKG_CLASS_PATH.'LibertyQuota.php' );
global $gBitSmarty, $assignUser, $gBitUser;

if( empty( $pQuotaUserId ) ) {
	if( !empty( $assignUser->mUserId ) ) {
		$pQuotaUserId = $assignUser->mUserId;
	} else {
		$pQuotaUserId = $gBitUser->mUserId;
	}
}

$quota = new LibertyQuota();
$diskUsage = $quota->getUserUsage( $pQuotaUserId );
$diskQuota = $quota->getUserQuota( $pQuotaUserId );

if( $diskQuota != 0 ) {
        $quotaPercent = round( (($diskUsage / $diskQuota) * 100), 0 );
} else {
        $quotaPercent = 0;
}

if( $quotaPercent > 100 ) {
        $errors['disk_quota'] = "You are over your disk quota.";
        $gBitSmarty->assignByRef( 'errors', $errors );
        $quotaPercent = 100;
}

$gBitSmarty->assign( 'usage', round( ($diskUsage / 1000000), 2 ) );
$gBitSmarty->assign( 'quota', round( ($diskQuota / 1000000), 2 ) );
$gBitSmarty->assignByRef( 'quotaPercent', $quotaPercent );
?>
