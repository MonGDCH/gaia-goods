<?php

declare(strict_types=1);

namespace plugins\goods\validate;

use mon\util\Validate;
use plugins\goods\contract\GoodsModelsEmun;

/**
 * 商品模型验证器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class ModelsValidate extends Validate
{
    /**
     * 验证规则
     *
     * @var array
     */
    public $rule = [
        'idx'       => ['required', 'id'],
        'name'      => ['required', 'str'],
        'remark'    => ['isset', 'str'],
        'status'    => ['required', 'status'],
        'bind'      => ['required', 'in:attr,spec'],
        'values'    => ['isset', 'str', 'listCheck:id']
    ];

    /**
     * 错误提示信息
     *
     * @var array
     */
    public $message = [
        'idx'       => '参数异常',
        'name'      => '请输入模型名称',
        'remark'    => '请输入合法的描述信息',
        'status'    => '请选择状态',
        'bind'      => '请选择绑定类型',
        'values'    => '绑定参数异常'
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    public $scope = [
        'add'   => ['name', 'remark', 'status'],
        'edit'  => ['idx', 'name', 'remark', 'status'],
        'bind'  => ['idx', 'bind', 'values']
    ];

    /**
     * 状态合法值
     *
     * @param string $value
     * @return boolean
     */
    public function status($value): bool
    {
        return isset(GoodsModelsEmun::MODELS_STATUS_TITLE[$value]);
    }
}
