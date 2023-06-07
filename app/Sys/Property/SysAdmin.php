<?php

namespace App\Sys\Property;

use App\Common\Property\AbstractProperty;

class SysAdmin extends AbstractProperty
{
    /**
     * @var int $id 主键
     */
    protected $id = 0;

    /**
     * @var int $role_id 用户角色id
     */
    protected $role_id;

    /**
     * @var string $state 该记录是否有效1：有效、0：无效
     */
    protected $username;

    /**
     * @var string $desc 描述
     */
    protected $password;

    /**
     * @var string $type 没啥用的东西
     */
    protected $nickname;

    /**
     * @var int $status 创建时间
     */
    protected $status;

    /**
     * @var string $last_login_time 最后的时间
     */
    protected $last_login_time;

    /**
     * @var string $create_time 创建时间
     */
    protected $create_time;

    /**
     * @var string $update_time 更新时间
     */
    protected $update_time;

}
