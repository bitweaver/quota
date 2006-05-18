<?php
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
?>
