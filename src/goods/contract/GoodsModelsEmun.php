<?php

declare(strict_types=1);

namespace plugins\goods\contract;

/**
 * 产品模型相关枚举属性
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
interface GoodsModelsEmun
{
    /**
     * 产品模型状态
     * 
     * @var array
     */
    const MODELS_STATUS = [
        // 禁用
        'disable'   => 0,
        // 正常
        'enable'    => 1,
    ];

    /**
     * 产品模型状态名称
     * 
     * @var array
     */
    const MODELS_STATUS_TITLE = [
        // 禁用
        self::MODELS_STATUS['disable']    => '禁用',
        // 正常
        self::MODELS_STATUS['enable']     => '正常',
    ];

    /**
     * 产品属性状态
     * 
     * @var array
     */
    const ATTR_STATUS = [
        // 禁用
        'disable'   => 0,
        // 正常
        'enable'    => 1,
    ];

    /**
     * 产品属性状态名称
     * 
     * @var array
     */
    const ATTR_STATUS_TITLE = [
        // 禁用
        self::ATTR_STATUS['disable']    => '禁用',
        // 正常
        self::ATTR_STATUS['enable']     => '正常',
    ];

    /**
     * 产品属性类型
     *
     * @var array
     */
    const ATTR_TYPE = [
        // 输入框
        'input'     => 0,
        // 单选
        'radio'     => 1,
        // 多选
        'checkbox'  => 2,
    ];

    /**
     * 产品属性类型描述
     *
     * @var array
     */
    const ATTR_TYPE_TITLE = [
        // 输入框
        self::ATTR_TYPE['input']    => '输入框',
        // 单选
        self::ATTR_TYPE['radio']    => '单选',
        // 多选
        self::ATTR_TYPE['checkbox'] => '多选',
    ];

    /**
     * 产品规格状态
     * 
     * @var array
     */
    const SPEC_STATUS = [
        // 禁用
        'disable'   => 0,
        // 正常
        'enable'    => 1,
    ];

    /**
     * 产品规格状态名称
     * 
     * @var array
     */
    const SPEC_STATUS_TITLE = [
        // 禁用
        self::SPEC_STATUS['disable']    => '禁用',
        // 正常
        self::SPEC_STATUS['enable']     => '正常',
    ];
}
