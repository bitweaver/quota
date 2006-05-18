<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_quota/index.php,v 1.4 2006/05/18 18:37:49 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: index.php,v 1.4 2006/05/18 18:37:49 squareing Exp $
 * @package quota
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'quota' );

require_once( QUOTA_PKG_PATH.'quota_inc.php' );

$gBitSystem->display( 'bitpackage:quota/quota.tpl', 'View Quota' );
?>
