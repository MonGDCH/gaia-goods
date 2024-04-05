<?php

declare(strict_types=1);

namespace plugins\goods\contract;

/**
 * 产品分类相关枚举属性
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
interface GoodsCateEmun
{
    /**
     * 产品分类状态
     * 
     * @var array
     */
    const CATE_STATUS = [
        // 禁用
        'disable'   => 0,
        // 正常
        'enable'    => 1,
    ];

    /**
     * 产品分类状态名称
     * 
     * @var array
     */
    const CATE_STATUS_TITLE = [
        // 禁用
        self::CATE_STATUS['disable']    => '禁用',
        // 正常
        self::CATE_STATUS['enable']     => '正常',
    ];
}
