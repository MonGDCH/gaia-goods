<?php

declare(strict_types=1);

namespace plugins\goods\contract;

/**
 * 配送模板相关枚举属性
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
interface ShippingTmpEmun
{
    /**
     * 模板状态
     * 
     * @var array
     */
    const TMP_STATUS = [
        // 禁用
        'disable'   => 0,
        // 正常
        'enable'    => 1,
    ];

    /**
     * 模板状态名称
     * 
     * @var array
     */
    const TMP_STATUS_TITLE = [
        // 禁用
        self::TMP_STATUS['disable'] => '禁用',
        // 正常
        self::TMP_STATUS['enable']  => '正常',
    ];

    /**
     * 模板类型
     * 
     * @var array
     */
    const TMP_TYPE = [
        // 数量
        'number'    => 0,
        // 重量
        'weight'    => 1,
        // 体积
        'volume'    => 2
    ];

    /**
     * 模板状态名称
     * 
     * @var array
     */
    const TMP_TYPE_TITLE = [
        // 数量
        self::TMP_TYPE['number']    => '按件数',
        // 重量
        self::TMP_TYPE['weight']    => '按重量',
        // 体积
        self::TMP_TYPE['volume']    => '按体积',
    ];
}
