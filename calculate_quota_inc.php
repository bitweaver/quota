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

$quota = new LibertyQuota();
if( !$gBitUser->isAdmin() && !$quota->isUserUnderQuota( $gBitUser->mUserId ) ) {
	$gBitSystem->display( 'bitpackage:quota/over_quota.tpl', tra( 'You are over your quota.' ) , array( 'display_mode' => 'display' ));
	die;
}

if( !$gBitUser->isAdmin() ) {
	// Prevent people from uploading more than their quota
	$q = $quota->getUserQuota( $gBitUser->mUserId );
	$u = $quota->getUserUsage( $gBitUser->mUserId );
	$gBitSmarty->assign( 'quotaMessage', tra( 'Your remaining disk quota is' ).' '.round( ( $q - $u ) / 1000000, 2 ).' '.tra( 'Megabytes' ) );
	$qMegs = round( $q / 1000000 );
	if( $qMegs < $uploadMax ) {
		$uploadMax = $qMegs;
	}
}
?>
