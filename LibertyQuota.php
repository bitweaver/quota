<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_quota/LibertyQuota.php,v 1.1.1.1.2.6 2005/08/08 07:15:30 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: LibertyQuota.php,v 1.1.1.1.2.6 2005/08/08 07:15:30 lsces Exp $
 * @package quota
 */

/**
 * required setup
 */
require_once( LIBERTY_PKG_PATH.'LibertyAttachable.php' );

/**
 * Quota class to illustrate best practices when creating a new bitweaver package that
 * builds on core bitweaver functionality, such as the Liberty CMS engine
 *
 * @package quota
 * @subpackage LibertyQuota
 *
 * created 2004/8/15
 *
 * @author spider <spider@steelsun.com>
 *
 * @version $Revision: 1.1.1.1.2.6 $ $Date: 2005/08/08 07:15:30 $ $Author: lsces $
 */
class LibertyQuota extends LibertyBase {
    /**
    * Primary key for our mythical Quota class object & table
    * @public
    */
	var $mQuotaId;

    /**
    * During initialisation, be sure to call our base constructors
	**/
	function LibertyQuota( $pQuotaId=NULL, $pContentId=NULL ) {
		$this->mQuotaId = $pQuotaId;
		LibertyBase::LibertyBase();
	}
	
	
    /**
    * Any method named Store inherently implies data will be written to the database
    * @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	**/
	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash ) ) {
			$this->mDb->StartTrans();
			$table = BIT_DB_PREFIX."tiki_quotas";
			if( $this->mQuotaId ) {
				$locId = array ( "name" => "quota_id", "value" => $pParamHash['quota_id'] );
				$result = $this->mDb->associateUpdate( $table, $pParamHash['quota_store'], $locId );
			} else {
				$this->mQuotaId = $this->mDb->GenID( 'tiki_quota_id_seq' );
				$pParamHash['quota_store']['quota_id'] = $this->mQuotaId;
				$result = $this->mDb->associateInsert( $table, $pParamHash['quota_store'] );
			}
			$this->load();
			$this->mDb->CompleteTrans();
		}
		return( count( $this->mErrors ) == 0 );
	}

    /**
    * Make sure the data is safe to store
    * @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	**/
	function verify( &$pParamHash ) {
		if( isset( $pParamHash['description'] ) ) {
			// insure we don't have column overflow, etc.
			$pParamHash['quota_store']['description'] = trim( $pParamHash['description'], 0, 160 );
		}
		if( !empty( $pParamHash['title'] ) ) {
			// insure we don't have column overflow, etc.
			$pParamHash['quota_store']['title'] = substr( trim( $pParamHash['title'] ), 0, 160 );
		} else {
			$this->mErrors['title'] = "Your quota needs a title";
		}

		if( empty( $pParamHash['disk_usage'] ) || !is_numeric( $pParamHash['disk_usage'] )  ) {
			$this->mErrors['disk_usage'] = "Invalid disk usage quantity";
		} else {
			$pParamHash['quota_store']['disk_usage'] = $pParamHash['disk_usage'] * 1000000;
		}

		if( empty( $pParamHash['monthly_transfer'] ) || !is_numeric( $pParamHash['monthly_transfer'] )  ) {
			$this->mErrors['monthly_transfer'] = "Invalid disk usage quantity";
		} else {
			$pParamHash['quota_store']['monthly_transfer'] = $pParamHash['monthly_transfer'] * 1000000;
		}

		return( count( $this->mErrors ) == 0 );
	}
	
    /**
    * Load the data from the database
    * @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	**/
	function load() {
		if( $this->mQuotaId ) {
			// LibertyContent::load() assumes you have joined already, and will not execute any sql!
			// This is a significant performance optimization
			$query = "SELECT tq.* FROM `".BIT_DB_PREFIX."tiki_quotas` tq WHERE tq.`quota_id`=?";
			$result = $this->mDb->query( $query, array( $this->mQuotaId ) );
			if ( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$query = "SELECT ug.`group_id`, ug.* FROM `".BIT_DB_PREFIX."users_groups` ug INNER JOIN `".BIT_DB_PREFIX."tiki_quotas_group_map` tqm ON( ug.`group_id`=tqm.`group_id` ) WHERE tqm.`quota_id`=?";
				if( $rs = $this->mDb->query( $query, array( $this->mQuotaId ) ) ) {
					$this->mInfo['quota_groups'] = $rs->fields;
				}
			}
		}
		return( count( $this->mInfo ) == 0 );
	}
	
    /**
    * 
	**/
	function getList() {
		$query = "SELECT tq.`quota_id`, tq.* FROM `".BIT_DB_PREFIX."tiki_quotas` tq";
		$ret = $this->mDb->getAssoc($query);
		return ( $ret );
	}

    /**
    * 
	**/
	function getQuotaMenu( $pName='quota_menu', $pSelectId=NULL ) {
		$query = "SELECT tq.`title`, tq.`quota_id` FROM `".BIT_DB_PREFIX."tiki_quotas` tq";
		if( $rs = $this->mDb->query($query) ) {
			$ret = $rs->GetMenu2( $pName, $pSelectId );
		}
		return ( $ret );
	}

	function getQuotaGroups() {
		$sql = "SELECT ug.`group_id`, ug.*, tqm.`quota_id`
				FROM `".BIT_DB_PREFIX."users_groups` ug LEFT OUTER JOIN `".BIT_DB_PREFIX."tiki_quotas_group_map` tqm ON( tqm.`group_id`=ug.`group_id` )
				WHERE ug.`user_id`=".ROOT_USER_ID."
				ORDER BY ug.`group_name` ASC";
		return $this->mDb->getAssoc( $sql );
	}

	

    /**
    * 
	**/
	function assignQuotaToGroup( $pQuotaId, $pGroupId ) {
		if( is_numeric( $pQuotaId ) && is_numeric( $pGroupId ) ) {
			$hasRow = $this->mDb->getOne( 'SELECT `quota_id` FROM  `'.BIT_DB_PREFIX.'tiki_quotas_group_map` WHERE `group_id`=?',array( $pGroupId ) );
 			if( $hasRow ) {
				$query = 'UPDATE `'.BIT_DB_PREFIX.'tiki_quotas_group_map` SET `quota_id`=? WHERE `group_id`=?';
				$rs = $this->mDb->query( $query, array( $pQuotaId, $pGroupId ) );
			} else {
				$query = 'INSERT INTO `'.BIT_DB_PREFIX.'tiki_quotas_group_map` (`quota_id`, `group_id`) VALUES (?,?)';
				$rs = $this->mDb->query( $query, array( $pQuotaId, $pGroupId ) );
			}
		} elseif( is_numeric( $pGroupId ) && empty( $pQuotaId ) ) {
			$query = 'DELETE FROM `'.BIT_DB_PREFIX.'tiki_quotas_group_map` WHERE `group_id`=?';
			$rs = $this->mDb->query( $query, array( $pGroupId ) );
		}
	}


    /**
    * returns the quota and consumption if a user is under usage level
	**/
	function isUserUnderQuota( $pUserId ) {
		$ret = FALSE;
		if( is_numeric( $pUserId) ) {
			$query = 'SELECT MAX(tq.`disk_usage`) AS `disk_usage` 
					  FROM `'.BIT_DB_PREFIX.'users_users` uu
						INNER JOIN `'.BIT_DB_PREFIX.'users_groups_map` ugm ON ( ugm.`user_id`=uu.`user_id` )
						INNER JOIN `'.BIT_DB_PREFIX.'tiki_quotas_group_map` tqm ON( tqm.`group_id`=ugm.`group_id` ) 
						INNER JOIN `'.BIT_DB_PREFIX.'tiki_quotas` tq ON( tq.`quota_id`=tqm.`quota_id` ) 
					  WHERE uu.`user_id`=?';
			if( $rs = $this->mDb->query( $query, array( $pUserId ) ) ) {
				$diskQuota = $rs->fields['disk_usage'];
				$diskConsumed = $this->getUserUsage( $pUserId );
				if( $diskQuota > $diskConsumed ) {
					$ret = array($diskQuota, $diskConsumed);
				}
			}
		}
		return $ret;
	}


	/**
	* Given a user_id, this will return the max quota for the given user. If the user belongs to more than one group, it will chose the max values
	* @param pUserId user_id of the user for usage to be calculated for
	* @returns an integer of the total bytes used
	*/
	function getUserQuota( $pUserId ) {
		$ret = 0;
		if( is_numeric( $pUserId ) ) {
			$query = 'SELECT MAX(tq.`disk_usage`) AS `disk_usage` 
					  FROM `'.BIT_DB_PREFIX.'users_users` uu
						INNER JOIN `'.BIT_DB_PREFIX.'users_groups_map` ugm ON ( ugm.`user_id`=uu.`user_id` )
						INNER JOIN `'.BIT_DB_PREFIX.'tiki_quotas_group_map` tqm ON( tqm.`group_id`=ugm.`group_id` ) 
						INNER JOIN `'.BIT_DB_PREFIX.'tiki_quotas` tq ON( tq.`quota_id`=tqm.`quota_id` ) 
					  WHERE uu.`user_id`=?';
			$ret = $this->mDb->getOne( $query, array( $pUserId ) );
		}
		return $ret;
	}

	/**
	* Given a user_id, this will return this disk space used for the given user
	* @param pUserId user_id of the user for usage to be calculated for
	* @returns an integer of the total bytes used
	*/
	function getUserUsage( $pUserId ) {
		$ret = 0;
		if( is_numeric( $pUserId ) ) {
			$ret = $this->mDb->getOne( "SELECT SUM(`size`) FROM `".BIT_DB_PREFIX."tiki_files` WHERE `user_id`=?", array( $pUserId ) );
		}
		return $ret;
	}

    /**
    * Generates the URL to the quota page
    * @param pExistsHash the hash that was returned by LibertyContent::pageExists
    * @return the link to display the page.
    */
	function getDisplayUrl() {
		$ret = NULL;
		if( !empty( $this->mQuotaId ) ) {
			$ret = QUOTA_PKG_URL."index.php?quota_id=".$this->mQuotaId;
		}
		return $ret;
	}
	
	function isValid() {
		return( !empty( $this->mQuotaId ) );
	}
	
}

?>
