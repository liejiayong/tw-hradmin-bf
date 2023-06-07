<?php

namespace App\Sys\Middle;

use App\Common\Middle\AbstractMiddleWare;
use App\Common\Service\LoginService;

class AdminMiddle extends AbstractMiddleWare
{
    /**
     * AdminController的login的中间件
     */
    public function login()
    {
        $this->setRequestField([
            'username',
            'password'
        ])->setRequestErrMsg([
            'username' => '管理员用户名',
            'password' => '密码'
        ])->setRequestAfter([

        ])->setRequestDefault([

        ]);
    }

    /**
     * AdminController的info的中间件
     */
    public function info()
    {
        $this->setRequestField([
            'token'
        ])->setRequestErrMsg([
            'token' => 'token令牌',
        ])->setRequestAfter([

        ])->setRequestDefault([

        ]);
    }

    /**
     * AdminController的addAdmin的中间件
     */
    public function addAdmin()
    {
        $this->setRequestField([
            'role_id',
            'username',
            'password',
            'nickname',
            'last_login_time',
            'status',
            'create_time',
            'update_time',
        ])->setRequestErrMsg([
        ])->setRequestAfter([

        ])->setRequestDefault([
            'password' => LoginService::instance()->genHashPwd('123456'),
            'status' => 1,
            'last_login_time' => getRequestTime(),
            'create_time' => getRequestTime(),
            'update_time' => getRequestTime()
        ]);
    }

    public function addRole()
    {
        $this->setRequestField([
            'name',
            'state',
            'desc',
            'type',
            'create_time',
            'update_time',
        ])->setRequestErrMsg([
        ])->setRequestAfter([

        ])->setRequestDefault([
            'state' => 1,
            'type' => 1,
            'create_time' => getRequestTime(),
            'update_time' => getRequestTime()
        ]);
    }

    public function updateRole()
    {
        $this->setRequestField([
            'id',
            'desc',
            'name'
        ])->setRequestErrMsg([
            'id' => '角色id',
            'desc' => '角色描述',
            'name' => '角色名字'
        ])->setRequestAfter([

        ])->setRequestDefault([
        ]);
    }

    public function updateUserRole()
    {
        $this->setRequestField([
            'role_id',
            'admin_id_array'
        ])->setRequestErrMsg([
        ])->setRequestAfter([
        ])->setRequestDefault([
        ]);
    }
}
