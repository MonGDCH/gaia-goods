<?php

declare(strict_types=1);

namespace plugins\goods\validate;

use mon\util\Validate;
use plugins\goods\contract\GoodsBrandEmun;

/**
 * 商品品牌验证器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class BrandValidate extends Validate
{
    /**
     * 验证规则
     *
     * @var array
     */
    public $rule = [
        'idx'       => ['required', 'id'],
        'cate_id'   => ['required', 'int', 'min:0'],
        'name'      => ['required', 'str'],
        'tel'       => ['isset', 'str'],
        'web'       => ['isset', 'str'],
        'logo'      => ['isset', 'str'],
        'remark'    => ['isset', 'str'],
        'status'    => ['required', 'status'],
        'sort'      => ['required', 'int', 'min:0', 'max:100'],
    ];

    /**
     * 错误提示信息
     *
     * @var array
     */
    public $message = [
        'idx'       => '参数异常',
        'cate_id'   => '请选择所属分类',
        'name'      => '请输入合法的品牌名称',
        'tel'       => '请输入合法的品牌联系方式',
        'web'       => '请输入合法的品牌官网地址',
        'logo'      => '请输入合法的品牌Logo',
        'remark'    => '请输入合法的品牌描述',
        'status'    => '请选择状态',
        'sort'      => '请输入正确的权重值(0 <= x < 100)',
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    public $scope = [
        'add'   => ['cate_id', 'name', 'tel', 'web', 'logo', 'remark', 'status', 'sort'],
        'edit'  => ['idx', 'cate_id', 'name', 'tel', 'web', 'logo', 'remark', 'status', 'sort'],
    ];

    /**
     * 状态合法值
     *
     * @param string $value
     * @return boolean
     */
    public function status($value): bool
    {
        return isset(GoodsBrandEmun::BRAND_STATUS_TITLE[$value]);
    }
}
