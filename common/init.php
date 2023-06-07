<?php
define('ROOT_DIR', dirname(__DIR__));
define('COMMON_DIR', ROOT_DIR.'/common');
define('LOG_DIR', ROOT_DIR.'/logs');
define('VIEW_DIR', ROOT_DIR.'/view');
define('PUBLIC_DIR', ROOT_DIR.'/public');
define('RUNTIME_DIR', ROOT_DIR.'/runtime');

require COMMON_DIR.'/config.php';
require COMMON_DIR.'/functions.php';
require dirname(__DIR__) . '/vendor/autoload.php';
//require dirname(__DIR__) . '/vendor/aliyun/aliyun-php-sdk-core/Config.php';
