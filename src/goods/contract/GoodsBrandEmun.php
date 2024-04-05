<?php

declare(strict_types=1);

namespace plugins\goods\contract;

/**
 * 产品品牌相关枚举属性
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
interface GoodsBrandEmun
{
    /**
     * 品牌状态
     * 
     * @var array
     */
    const BRAND_STATUS = [
        // 禁用
        'disable'   => 0,
        // 正常
        'enable'    => 1,
    ];

    /**
     * 品牌状态名称
     * 
     * @var array
     */
    const BRAND_STATUS_TITLE = [
        // 禁用
        self::BRAND_STATUS['disable']    => '禁用',
        // 正常
        self::BRAND_STATUS['enable']     => '正常',
    ];
}
