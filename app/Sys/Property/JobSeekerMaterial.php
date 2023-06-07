<?php

namespace App\Sys\Property;

use App\Common\Property\AbstractProperty;

/**
 * 求职者材料
 *
 * Class JobSeekerMaterial
 * @package App\Sys\Property
 */
class JobSeekerMaterial extends AbstractProperty
{
    /**
     * @var int $id 主键
     */
    protected $id = 0;

    /**
     * @var int $key 材料唯一key
     */
    protected $key;

    /**
     * @var string $name 求职者姓名
     */
    protected $name;

    /**
     * @var string $data  材料内容
     */
    protected $data;

    /**
     * @var int $creator_id 创建人id
     */
    protected $creator_id;

    /**
     * @var int $create_time 创建时间
     */
    protected $create_time;

    /**
     * @var int $update_time 更新时间
     */
    protected $update_time;
}
