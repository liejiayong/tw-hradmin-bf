<?php

$lifeTime = 24 * 3600; // session有效期
session_set_cookie_params($lifeTime);
session_start();

require dirname(__DIR__) . '/common/init.php';

//开启php调试模式
if (DEBUG) {
    ini_set('display_errors', 'On');
    error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
}

use App\Common\Object\DB;
use Illuminate\Database\Capsule\Manager;


define('IS_POST', $_SERVER['REQUEST_METHOD'] == 'POST');

date_default_timezone_set('PRC');

//统一控制请求数据
$post_json = file_get_contents('php://input');
if ($post_json) {
    $request = checkData((json_decode($post_json, true)));
    if (is_array($request)) {
        $_POST = array_merge($_POST, $request);
    }
}

if ($_GET) {
    $_GET = checkData($_GET);
}

$globalRequest = array_merge($_POST, $_GET);
$globalRequestField = [];

$projectName = isset($globalRequest['p']) ? ucfirst($globalRequest['p']) : 'Sys';
$controllerName = isset($globalRequest['c']) ? ucfirst($globalRequest['c']) : 'Index';
$methodName = isset($globalRequest['m']) ? $globalRequest['m'] : 'index';

//设置访问后台ip白名单
//$ip = get_client_ip();
//if ($projectName == 'Sys' && !in_array($ip, ['183.62.22.202', '127.0.0.1', '59.41.129.10', '183.6.26.143', '58.63.228.50', '113.108.150.83', '59.42.177.74',
//        '183.6.121.99', '120.239.72.208', '106.4.228.192', '111.75.6.173', '106.4.229.64', '183.6.26.100', '113.108.156.250', '111.75.10.75', '111.75.6.245'])) {
//    header('HTTP/1.1 404 Not Found');exit;
//}

/**
 * 设置全局DB对象
 * @var Manager $manager
 */
$manager = new Manager();
if (IS_SEVER) {
    $manager->addConnection(DB_LIST['server']);
} else {
    $manager->addConnection(DB_LIST['local']);
}

$manager->setAsGlobal();
DB::setDBInstance($manager);
if (DEBUG) {
    DB::connection()->enableQueryLog();
}
$middleClass = "App\\{$projectName}\\Middle\\" . ($controllerName) . 'Middle';
if (class_exists($middleClass)) {
    /** @var \App\Common\Middle\AbstractMiddleWare $middleWare */
    $middleWare = new $middleClass;
    if (method_exists($middleWare, $methodName)) {
        $middleWare->$methodName();
        $middleWare->takeMiddleData();
    }
}
$controllerClass = "App\\{$projectName}\\Controller\\" . ($controllerName) . 'Controller';
if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
    if (method_exists($controller, $methodName)) {
        $controller->$methodName();
    } else {
        header('HTTP/1.1 404 Not Found');exit;
    }
} else {
    header('HTTP/1.1 404 Not Found');exit;
}
