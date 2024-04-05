<?php

declare(strict_types=1);

namespace plugins\goods\validate;

use mon\util\Validate;
use plugins\goods\contract\GoodsModelsEmun;

/**
 * 商品规格验证器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class SpecValidate extends Validate
{
    /**
     * 验证规则
     *
     * @var array
     */
    public $rule = [
        'idx'       => ['required', 'id'],
        'name'      => ['required', 'str'],
        'content'   => ['required', 'str'],
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
        'add'   => ['name', 'content', 'remark', 'status'],
        'edit'  => ['idx', 'name', 'content', 'remark', 'status'],
    ];

    /**
     * 状态合法值
     *
     * @param string $value
     * @return boolean
     */
    public function status($value): bool
    {
        return isset(GoodsModelsEmun::SPEC_STATUS_TITLE[$value]);
    }
}
