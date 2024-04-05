<?php

declare(strict_types=1);

namespace plugins\goods\validate;

use mon\util\Validate;
use plugins\goods\contract\GoodsCateEmun;

/**
 * 商品分类验证器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class CateValidate extends Validate
{
    /**
     * 验证规则
     *
     * @var array
     */
    public $rule = [
        'idx'       => ['required', 'id'],
        'pid'       => ['required', 'int', 'min:0'],
        'name'      => ['required', 'str'],
        'status'    => ['required', 'status'],
        'sort'      => ['required', 'int', 'min:0', 'max:100'],
        'img'       => ['isset', 'str'],
        'remark'    => ['isset', 'str']
    ];

    /**
     * 错误提示信息
     *
     * @var array
     */
    public $message = [
        'idx'       => '参数异常',
        'pid'       => '请选择合法的父级分类',
        'name'      => '请输入合法的分类名称',
        'status'    => '请选择状态',
        'sort'      => '请输入正确的权重值(0 <= x <= 100)',
        'img'       => '请输入合法的图片地址',
        'remark'    => '请输入合法的分类备注'
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    public $scope = [
        'add'   => ['pid', 'name', 'status', 'sort', 'img', 'remark'],
        'edit'  => ['idx', 'pid', 'name', 'status', 'sort', 'img', 'remark'],
    ];

    /**
     * 状态合法值
     *
     * @param string $value
     * @return boolean
     */
    public function status($value): bool
    {
        return isset(GoodsCateEmun::CATE_STATUS_TITLE[$value]);
    }
}
