<?php

declare(strict_types=1);

namespace plugins\goods\validate;

use mon\util\Validate;
use plugins\goods\contract\ShippingTmpEmun;

/**
 * 配送模板验证器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class ShippingTmpValidate extends Validate
{
    /**
     * 验证规则
     *
     * @var array
     */
    public $rule = [
        'idx'               => ['required', 'id'],
        'type'              => ['required', 'type'],
        'name'              => ['required', 'str'],
        'remark'            => ['isset', 'str'],
        'first'             => ['required', 'num', 'min:0'],
        'first_price'       => ['required', 'num', 'min:0'],
        'continue'          => ['required', 'num', 'min:0'],
        'continue_price'    => ['required', 'num', 'min:0'],
        'sort'              => ['required', 'int', 'min:0', 'max:100'],
        'status'            => ['required', 'status'],
    ];

    /**
     * 错误提示信息
     *
     * @var array
     */
    public $message = [
        'idx'               => '参数异常',
        'type'              => '请选择计费方式',
        'name'              => '请输入模板名称',
        'remark'            => '请输入模板备注',
        'first'             => '请输入首件数量',
        'first_price'       => '请输入首件价格',
        'continue'          => '请输入续件数量',
        'continue_price'    => '请输入续件价格',
        'sort'              => '请输入排序权重',
        'status'            => '请选择状态',
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    public $scope = [
        // 新增
        'add'   => ['type', 'name', 'first', 'first_price', 'continue', 'continue_price', 'sort', 'status'],
        // 编辑
        'edit'  => ['idx', 'type', 'name', 'first', 'first_price', 'continue', 'continue_price', 'sort', 'status'],
    ];

    /**
     * 类型合法值
     *
     * @param string $value
     * @return boolean
     */
    public function type($value): bool
    {
        return isset(ShippingTmpEmun::TMP_TYPE_TITLE[$value]);
    }

    /**
     * 状态合法值
     *
     * @param string $value
     * @return boolean
     */
    public function status($value): bool
    {
        return isset(ShippingTmpEmun::TMP_STATUS_TITLE[$value]);
    }
}
