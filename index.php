<?php
/**
 * $Header$
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id$
 * @package quota
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );

$gBitSystem->verifyPackage( 'quota' );

require_once( QUOTA_PKG_INCLUDE_PATH.'quota_inc.php' );

$gBitSystem->display( 'bitpackage:quota/quota.tpl', 'View Quota' , array( 'display_mode' => 'display' ));
?>
