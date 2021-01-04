<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
session_start();
////////////////////////////////////////////////////////////////////////////////
// Configure the default time zone
////////////////////////////////////////////////////////////////////////////////
date_default_timezone_set('Asia/Kolkata');

////////////////////////////////////////////////////////////////////////////////
// Configure the default currency
////////////////////////////////////////////////////////////////////////////////
setlocale(LC_MONETARY, 'en_US');

////////////////////////////////////////////////////////////////////////////////
// Define constants for database connectivty
////////////////////////////////////////////////////////////////////////////////

defined('DATABASE_HOST') ? NULL : define('DATABASE_HOST', 'localhost');
defined('DATABASE_NAME') ? NULL : define('DATABASE_NAME', 'ws_weddingplanner');
defined('DATABASE_USER') ? NULL : define('DATABASE_USER', 'root');
defined('DATABASE_PASSWORD') ? NULL : define('DATABASE_PASSWORD', ''); //4Qp6EC(}uzqs

////////////////////////////////////////////////////////////////////////////////
// Define absolute application paths
////////////////////////////////////////////////////////////////////////////////

// Use PHP's directory separator for windows/unix compatibility
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);
defined('RDS') ? NULL : define('RDS', '/');
// Project Name
defined('PROJECTTITLE') ? NULL : define('PROJECTTITLE', 'Wedding Planner');
// Define relative path to server root
defined('SITE_URL') ? NULL : define('SITE_URL', 'http://localhost/weddingplanner/');
defined('ADMINROOT') ? NULL : define('ADMINROOT', 'http://localhost/weddingplanner/planner-console/');
defined('ADMINIMAGEROOT') ? NULL : define('ADMINIMAGEROOT', 'http://localhost/weddingplanner/planner-console/assets/images/');
defined('FRONTIMAGEROOT') ? NULL : define('FRONTIMAGEROOT', 'http://localhost/weddingplanner/assets/images/');
// Define absolute path to server root
defined('SITE_ROOT') ? NULL : define('SITE_ROOT', dirname(dirname(__FILE__)) . DS);
// Define absolute path to includes
defined('INCLUDE_PATH') ? NULL : define('INCLUDE_PATH', SITE_ROOT . 'includes' . DS);
defined('FUNCTION_PATH') ? NULL : define('FUNCTION_PATH', INCLUDE_PATH . 'functions' . DS);

defined('LIB_PATH') ? NULL : define('LIB_PATH', INCLUDE_PATH . 'libraries' . DS);

// defined('MODEL_PATH') ? NULL : define('MODEL_PATH', SITE_ROOT . 'weddingplanner' . DS . 'models' . DS);
// defined('VIEW_PATH') ? NULL : define('VIEW_PATH', SITE_ROOT . 'weddingplanner' . DS . 'views' . DS);

defined('MODEL_PATH') ? NULL : define('MODEL_PATH', SITE_ROOT . DS . 'models' . DS);
defined('VIEW_PATH') ? NULL : define('VIEW_PATH', SITE_ROOT . DS . 'views' . DS);

defined('LOGIN_PATH') ? NULL : define('LOGIN_PATH', 'http://localhost/weddingplanner/' . DS);
defined('UPLOADS_PATH') ? NULL : define('UPLOADS_PATH', SITE_ROOT . 'uploads' . DS);

// defined('CSS_URL') ? NULL : define('CSS_URL', SITE_URL . 'css' . RDS);
// defined('JS_URL') ? NULL : define('JS_URL', SITE_URL . 'js' . RDS);
// defined('INCLUDE_URL') ? NULL : define('INCLUDE_URL', SITE_URL . 'includes' . RDS);
// defined('UPLOADS_URL') ? NULL : define('UPLOADS_URL', SITE_URL . 'uploads' . RDS);

////////////////////////////////////////////////////////////////////////////////
// Include library, helpers, functions
////////////////////////////////////////////////////////////////////////////////
require_once FUNCTION_PATH . 'functions.inc.php';
require_once LIB_PATH . 'database.class.php';
?>