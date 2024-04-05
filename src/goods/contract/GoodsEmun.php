<?php

declare(strict_types=1);

namespace plugins\goods\contract;

/**
 * 产品相关枚举属性
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
interface GoodsEmun
{
    /**
     * 产品数据类型
     * 
     * @var array
     */
    const GOODS_SECNE_TYPE = [
        // 仓库中
        'warehouse' => 0,
        // 销售中
        'sale'      => 1,
        // 库存预警
        'alert'     => 2,
        // 预算中
        'pre_sale'  => 3,
        // 未上架
        'not_sale'  => 4,
        // 所有产品
        'all'       => 5
    ];

    /**
     * 产品状态
     * 
     * @var array
     */
    const GOODS_STATUS = [
        // 待审核
        'audit'     => 0,
        // 已通过
        'pass'      => 1,
        // 已驳回
        'reject'    => 2,
    ];

    /**
     * 产品状态名称
     * 
     * @var array
     */
    const GOODS_STATUS_TITLE = [
        // 待审核
        self::GOODS_STATUS['audit']     => '待审核',
        // 已通过
        self::GOODS_STATUS['pass']      => '已通过',
        // 已驳回
        self::GOODS_STATUS['reject']    => '已驳回',
    ];

    /**
     * 产品销售状态
     * 
     * @var array
     */
    const GOODS_SALE_STATUS = [
        // 未上架
        'not_sale'  => 0,
        // 已上架
        'sale'      => 1,
        // 预售中
        'pre_sale'  => 2,
        // 违规下架
        'violation' => 3
    ];

    /**
     * 产品销售状态名称
     * 
     * @var array
     */
    const GOODS_SALE_STATUS_TITLE = [
        // 未上架
        self::GOODS_SALE_STATUS['not_sale']     => '未上架',
        // 已上架
        self::GOODS_SALE_STATUS['sale']         => '已上架',
        // 预售中
        self::GOODS_SALE_STATUS['pre_sale']     => '预售中',
        // 违规下架
        self::GOODS_SALE_STATUS['violation']    => '违规下架',
    ];

    /**
     * 产品类型
     * 
     * @var array
     */
    const VIRTUAL_TYPE = [
        // 实物产品
        'entity'    => 0,
        // 虚拟产品
        'virtually' => 1,
    ];

    /**
     * 产品类型名称
     * 
     * @var array
     */
    const VIRTUAL_TYPE_TITLE = [
        // 实物产品
        self::VIRTUAL_TYPE['entity']    => '实物产品',
        // 虚拟产品
        self::VIRTUAL_TYPE['virtually'] => '虚拟产品',
    ];

    /**
     * 产品配送类型
     * 
     * @var array
     */
    const SHIPPING_STATUS = [
        // 不需要配送
        'disable'   => 0,
        // 需要配送
        'enable'    => 1,
    ];

    /**
     * 产品配送类型名称
     * 
     * @var array
     */
    const SHIPPING_STATUS_TITLE = [
        // 不需要配送
        self::SHIPPING_STATUS['disable']  => '无需配送',
        // 需要配送
        self::SHIPPING_STATUS['enable']   => '需要配送',
    ];

    /**
     * 是否限购状态
     * 
     * @var array
     */
    const BUY_LIMIT_STATUS = [
        // 不限购
        'disable'   => 0,
        // 限购
        'enable'    => 1,
    ];

    /**
     * 是否限购状态名称
     * 
     * @var array
     */
    const BUY_LIMIT_STATUS_TITLE = [
        // 不限购
        self::BUY_LIMIT_STATUS['disable']  => '不限购',
        // 限购
        self::BUY_LIMIT_STATUS['enable']   => '限购',
    ];


    /**
     * 限购方式类型
     * 
     * @var array
     */
    const BUY_LIMIT_TYPE = [
        // 无限制
        'disable'   => 0,
        // 总数
        'count'     => 1,
        // 按天
        'day'       => 2,
        // 按周
        'week'      => 3,
        // 按月
        'month'     => 4
    ];

    /**
     * 限购方式类型名称
     * 
     * @var array
     */
    const BUY_LIMIT_TYPE_TITLE = [
        // 无限制
        self::BUY_LIMIT_TYPE['disable'] => '无限制',
        // 总数
        self::BUY_LIMIT_TYPE['count']   => '总数',
        // 按天
        self::BUY_LIMIT_TYPE['day']     => '按天',
        // 按周
        self::BUY_LIMIT_TYPE['week']    => '按周',
        // 按月
        self::BUY_LIMIT_TYPE['month']   => '按月'
    ];

    /**
     * 扣减库存状态
     * 
     * @var array
     */
    const STOCK_REDUCE_TYPE = [
        // 不扣减库存
        'disable'   => 0,
        // 扣减库存
        'enable'    => 1,
    ];

    /**
     * 扣减库存状态名称
     * 
     * @var array
     */
    const STOCK_REDUCE_TYPE_TITLE = [
        // 不扣减库存
        self::STOCK_REDUCE_TYPE['disable']  => '不扣减库存',
        // 扣减库存
        self::STOCK_REDUCE_TYPE['enable']   => '扣减库存',
    ];

    /**
     * 是否显示库存状态
     * 
     * @var array
     */
    const STOCK_VISIBLE_TYPE = [
        // 不显示库存
        'disable'   => 0,
        // 显示库存
        'enable'    => 1,
    ];

    /**
     * 是否显示库存状态名称
     * 
     * @var array
     */
    const STOCK_VISIBLE_TYPE_TITLE = [
        // 不显示库存
        self::STOCK_VISIBLE_TYPE['disable']  => '不显示库存',
        // 显示库存
        self::STOCK_VISIBLE_TYPE['enable']   => '显示库存',
    ];

    /**
     * 是否显示销量状态
     * 
     * @var array
     */
    const SALE_VISIBLE_TYPE = [
        // 不显示销量
        'disable'   => 0,
        // 显示销量
        'enable'    => 1,
    ];

    /**
     * 是否显示销量状态名称
     * 
     * @var array
     */
    const SALE_VISIBLE_TYPE_TITLE = [
        // 不显示销量
        self::SALE_VISIBLE_TYPE['disable']  => '不显示销量',
        // 显示销量
        self::SALE_VISIBLE_TYPE['enable']   => '显示销量',
    ];

    /**
     * 是否热门状态
     * 
     * @var array
     */
    const HOT_STATUS = [
        // 非热门
        'disable'   => 0,
        // 热门
        'enable'    => 1,
    ];

    /**
     * 是否热门状态名称
     * 
     * @var array
     */
    const HOT_STATUS_TITLE = [
        // 非热门
        self::HOT_STATUS['disable']  => '非热门',
        // 热门
        self::HOT_STATUS['enable']   => '热门',
    ];

    /**
     * 是否推荐状态
     * 
     * @var array
     */
    const RECOMMEND_STATUS = [
        // 不推荐
        'disable'   => 0,
        // 推荐
        'enable'    => 1,
    ];

    /**
     * 是否推荐状态名称
     * 
     * @var array
     */
    const RECOMMEND_STATUS_TITLE = [
        // 不推荐
        self::RECOMMEND_STATUS['disable']  => '非推荐',
        // 推荐
        self::RECOMMEND_STATUS['enable']   => '推荐',
    ];

    /**
     * 是否新品状态
     * 
     * @var array
     */
    const NEW_STATUS = [
        // 非新品
        'disable'   => 0,
        // 新品
        'enable'    => 1,
    ];

    /**
     * 是否新品状态名称
     * 
     * @var array
     */
    const NEW_STATUS_TITLE = [
        // 非新品
        self::NEW_STATUS['disable']  => '非新品',
        // 新品
        self::NEW_STATUS['enable']   => '新品',
    ];

    /**
     * 是否开具发票状态
     * 
     * @var array
     */
    const BILL_STATUS = [
        // 不开具发票
        'disable'   => 0,
        // 开具发票
        'enable'    => 1,
    ];

    /**
     * 是否开具发票状态名称
     * 
     * @var array
     */
    const BILL_STATUS_TITLE = [
        // 不开具发票
        self::BILL_STATUS['disable']  => '不开具发票',
        // 开具发票
        self::BILL_STATUS['enable']   => '开具发票',
    ];
}
