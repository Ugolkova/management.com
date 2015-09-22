<?php

define( 'HOST', 'management.com' );
define( 'URL', 'http://' . HOST . '/' );
define( 'LIBS', 'libs/' );

define( 'SITE_NAME', 'Management.com' );

define( 'DB_TYPE', 'mysql' );
define( 'DB_HOST', 'localhost' );
define( 'DB_NAME', 'management' );
define( 'DB_USER', 'root' );
define( 'DB_PASS', 'root' );

$GLOBALS['COUNTS_ENTRIES_AVAILABLE'] = array(2, 5, 10, 20);
define( 'COUNT_ENTRIES_ON_PAGE', $GLOBALS['COUNTS_ENTRIES_AVAILABLE'][0] );

define( 'HASH_GENERAL_KEY', 'gf<<qJ(&oeXehfE' );
define( 'HASH_PASSWORD_KEY', 'catsFLYhigh2000miles' );

define( 'MAIN_DELIMITER', ', ' );