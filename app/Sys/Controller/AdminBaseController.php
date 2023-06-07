<?php

namespace App\Sys\Controller;

use App\Common\Controller\AbstractController;
use App\Common\Response\Response;
use App\Common\Response\ResponseErrorConfig;

class AdminBaseController extends AbstractController
{
    /**
     * 当前管理员的session数据
     * @var array
     */
    protected $adminInfo = [];

    public $unloginAction = [];

    public function __construct()
    {
        parent::__construct();
        global $methodName;
        if (!in_array($methodName, $this->unloginAction)) {
//            $this->authentication();
            $this->checkLogin();
            $this->adminInfo = $_SESSION['user_info'];
        }
    }

    /**
     * 登录验证
     */
    protected function checkLogin()
    {
        if (!$this->checkSession()) {
            Response::instance()->failJson([], '', ResponseErrorConfig::LOGOUT);
        }
    }

    /**
     * 验证是否有session
     * @return bool
     */
    private function checkSession()
    {
        if (isset($_SESSION['token'], $_SESSION['user_info']) && $_SESSION['token'] && $_SESSION['user_info']) {
            return true;
        }
        return false;
    }

    /**
     * 权限验证
     */
//    protected function authentication()
//    {
////        $permission_uri_keys = array_intersect(array_keys(static::URI), $_SESSION['permission_uri']);
//        $permission_uri_keys = array_values($_SESSION['permission_uri']);
//        $permission_uri = ['info'];
//        $permission_uri = array_merge($permission_uri, $permission_uri_keys);
////        foreach ($permission_uri_keys as $permission_uri_key) {
////            $permission_uri = array_merge(static::URI[$permission_uri_key], $permission_uri);
////        }
////        isset(static::URI['/all']) && $permission_uri = array_merge(static::URI['/all'], $permission_uri);
//        global $method_name;
//        global $controller_name;
//        $request_uri = strtolower("/{$controller_name}/{$method_name}");
//        if (!in_array($request_uri, $permission_uri)) {
//            Response::instance()->json([
//                'code' => ResponseCode::FAILURE,
//                'msg' => '你没有权限访问，请联系管理员',
//            ]);
//        }
//    }
}
