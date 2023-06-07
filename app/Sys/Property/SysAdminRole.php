<?php

namespace App\Sys\Property;

use App\Common\Property\AbstractProperty;

/**
 * Created by PhpStorm.
 * User: zzh
 * Date: 2019/6/20
 * Time: 15:38
 */
//礼包提示库

class SysAdminRole extends AbstractProperty
{
    /**
     * @var int $id 主键
     */
    protected $id = 0;

    /**
     * @var string $name 角色名
     */
    protected $name;

    /**
     * @var string $state 该记录是否有效1：有效、0：无效
     */
    protected $state;

    /**
     * @var string $desc 描述
     */
    protected $desc;

    /**
     * @var int $type 没啥用的东西
     */
    protected $type;

    /**
     * @var string $create_time 创建时间
     */
    protected $create_time;

    /**
     * @var string $update_time 更新时间
     */
    protected $update_time;

}
