<?php

declare(strict_types=1);

namespace plugins\goods\validate;

use mon\util\Validate;
use plugins\goods\contract\GoodsModelsEmun;

/**
 * 商品属性验证器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class AttrValidate extends Validate
{
    /**
     * 验证规则
     *
     * @var array
     */
    public $rule = [
        'idx'       => ['required', 'id'],
        'type'      => ['required', 'type'],
        'name'      => ['required', 'str'],
        'content'   => ['isset', 'str'],
        'remark'    => ['isset', 'str'],
        'status'    => ['required', 'status'],
    ];

    /**
     * 错误提示信息
     *
     * @var array
     */
    public $message = [
        'idx'       => '参数异常',
        'type'      => '请选择录入方式',
        'name'      => '请输入属性名称',
        'content'   => '请输入合法的属性值',
        'remark'    => '请输入合法的描述信息',
        'status'    => '请选择状态',
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    public $scope = [
        'add'   => ['type', 'name', 'content', 'remark', 'status'],
        'edit'  => ['idx', 'type', 'name', 'content', 'remark', 'status'],
    ];

    /**
     * 类型合法值
     *
     * @param string $value
     * @return boolean
     */
    public function type($value): bool
    {
        return isset(GoodsModelsEmun::ATTR_TYPE_TITLE[$value]);
    }

    /**
     * 状态合法值
     *
     * @param string $value
     * @return boolean
     */
    public function status($value): bool
    {
        return isset(GoodsModelsEmun::ATTR_STATUS_TITLE[$value]);
    }
}
