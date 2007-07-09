<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_quota/admin/pump_quota_inc.php,v 1.1 2007/07/09 13:16:15 squareing Exp $
 * @package install
 * @subpackage pumps
 */

/**
 * required setup
 */
require_once( QUOTA_PKG_PATH.'LibertyQuota.php' );

$quota = new LibertyQuota();
$quota->mDb->Execute("INSERT INTO `".BIT_DB_PREFIX."quotas` ( `quota_id`, `disk_usage`, `monthly_transfer`, `title`, `description` ) VALUES ('1', 2000000, 20000000, 'Free Trial', 'A little space to try out site features' )");
$quota->mDb->Execute("INSERT INTO `".BIT_DB_PREFIX."quotas` ( `quota_id`, `disk_usage`, `monthly_transfer`, `title`, `description` ) VALUES ('2', 10000000, 100000000, 'Site Supporters', 'Extra space for site supporters.' )");
$quota->mDb->Execute("INSERT INTO `".BIT_DB_PREFIX."quotas_group_map` ( `quota_id`, `group_id` ) VALUES ( 1, 3 )");
$quota->mDb->Execute( "INSERT INTO `".BIT_DB_PREFIX."quotas_group_map` ( `quota_id`, `group_id` ) VALUES ( 2, 2 )");

$pumpedData['Quota'][] = 'Free Trial';
$pumpedData['Quota'][] = 'Site Supporters';

