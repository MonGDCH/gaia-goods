<?php

declare(strict_types=1);

namespace plugins\goods\validate;

use mon\util\Validate;
use plugins\goods\contract\GoodsEmun;

/**
 * 商品验证器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class GoodsValidate extends Validate
{
    /**
     * 验证规则
     *
     * @var array
     */
    public $rule = [
        'idx'               => ['required', 'id'],
        'business_id'       => ['required', 'int', 'min:0'],
        'cate_cat'          => ['required', 'listCheck:required,id'],
        'brand_id'          => ['required', 'int', 'min:0'],
        'virtual'           => ['required', 'virtual'],
        'name'              => ['required', 'str'],
        'valuation'         => ['required', 'money'],
        'thumb'             => ['required', 'str'],
        'marque'            => ['isset', 'str'],
        'barcode'           => ['isset', 'str'],
        'remark'            => ['isset', 'str'],
        'sort'              => ['required', 'int', 'min:0', 'max:100'],
        'unit'              => ['required', 'str'],
        'shipping'          => ['required', 'shipping'],
        'shipping_id'       => ['required', 'int', 'min:0'],
        'buy_limit'         => ['required', 'buyLimit'],
        'buy_limit_type'    => ['required', 'buyLimitType'],
        'buy_limit_round'   => ['required', 'int', 'min:0'],
        'buy_limit_max'     => ['required', 'int', 'min:0'],
        'buy_limit_min'     => ['required', 'int', 'min:0'],
        'stock_reduce'      => ['required', 'stockReduce'],
        'stock_visible'     => ['required', 'stockVisible'],
        'sales_visible'     => ['required', 'salesVisible'],
        'is_hot'            => ['required', 'isHot'],
        'is_recommend'      => ['required', 'isRecommend'],
        'is_new'            => ['required', 'isNew'],
        'is_bill'           => ['required', 'isBill'],
        'view_num'          => ['required', 'int', 'min:0'],
        'star_num'          => ['required', 'int', 'min:0'],
        'collect_num'       => ['required', 'int', 'min:0'],
        'comment_num'       => ['required', 'int', 'min:0'],
        'transmit_num'      => ['required', 'int', 'min:0'],
        'sale_start_time'   => ['required', 'timestamp'],
        'sale_end_time'     => ['required', 'timestamp'],
        'sale_status'       => ['required', 'saleStatus'],
        'status'            => ['required', 'status'],

        'content'           => ['isset', 'str'],
        'attrs'             => ['isset', 'json'],
        'specs'             => ['required', 'json'],
        'covers'            => ['isset', 'str'],
        'imgs'              => ['isset', 'str', 'listCheck:required'],
        'recommends'        => ['isset', 'str', 'listCheck:required,id'],
        'seo_title'         => ['isset', 'str'],
        'seo_keyword'       => ['isset', 'str'],
        'seo_desc'          => ['isset', 'str'],

        'skus'              => ['required', 'json'],
        'sku'               => ['required', 'json'],
        'img'               => ['isset', 'str'],
        'price'             => ['required', 'money'],
        'cost_price'        => ['required', 'money'],
        'market_price'      => ['required', 'money'],
        'stock'             => ['required', 'int', 'min:0'],
        'sales'             => ['required', 'int', 'min:0'],
        'code'              => ['isset', 'str'],
        'weight'            => ['required', 'num', 'min:0'],
        'volume'            => ['required', 'num', 'min:0'],
    ];

    /**
     * 错误提示信息
     *
     * @var array
     */
    public $message = [
        'idx'               => '参数异常',
        'business_id'       => '商户ID异常',
        'cate_cat'          => '请选择分类',
        'brand_id'          => '请选择品牌',
        'virtual'           => '请选择产品类型',
        'name'              => '请输入产品名称',
        'valuation'         => '请输入产品参考价',
        'marque'            => '请输入产品型号',
        'barcode'           => '请输入产品条码',
        'remark'            => '请输入产品简述',
        'thumb'             => '请上传产品主图',
        'sort'              => '请输入产品排序权重',
        'unit'              => '请输入产品计量单位',
        'shipping'          => '请选择是否需要配送',
        'shipping_id'       => '请选择配送模板',
        'buy_limit'         => '请选择是否设置购买限制',
        'buy_limit_type'    => '请选择限购类型',
        'buy_limit_round'   => '请输入限购天数',
        'buy_limit_max'     => '请输入产品最大购买数',
        'buy_limit_min'     => '请输入产品起购数',
        'stock_reduce'      => '请选择是否扣减库存',
        'stock_visible'     => '请选择是否显示库存',
        'sales_visible'     => '请选择是否显示销量',
        'is_hot'            => '请选择产品是否热门',
        'is_recommend'      => '请选择产品是否推荐',
        'is_new'            => '请选择产品是否新品',
        'is_bill'           => '请选择产品是否开具发票',
        'view_num'          => '请输入产品查看数',
        'star_num'          => '请输入产品点赞数',
        'collect_num'       => '请输入产品收藏数',
        'comment_num'       => '请输入产品评论数',
        'transmit_num'      => '请输入产品回复数',
        'sale_start_time'   => '请选择上架时间',
        'sale_end_time'     => '请选择下架时间',
        'sale_status'       => '请选择销售状态',
        'status'            => '请选择状态',

        'content'           => '请输入产品详情',
        'attrs'             => '请选择填写产品相关属性',
        'specs'             => '请选择填写产品相关规格',
        'covers'            => '请上传轮播图库',
        'imgs'              => '请上传轮播图图片',
        'recommends'        => '请选择推荐的关联产品',
        'seo_title'         => '请输入SEO标题',
        'seo_keyword'       => '请输入SEO关键字',
        'seo_desc'          => '请输入SEO描述',

        'skus'              => '请生成产品sku',
        'sku'               => '产品sku异常',
        'img'               => '请上传sku产品图片',
        'price'             => '请输入sku产品价格',
        'cost_price'        => '请输入sku产品成本价格',
        'market_price'      => '请输入sku产品市场价格',
        'stock'             => '请输入sku产品库存',
        'sales'             => '请输入sku产品销量',
        'code'              => '请输入sku产品编码',
        'weight'            => '请输入sku产品重量',
        'volume'            => '请输入sku产品体积',
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    public $scope = [
        // 新增
        'add' => [
            'cate_cat', 'brand_id', 'virtual', 'name', 'marque', 'barcode', 'remark', 'thumb', 'sort', 'unit', 'attrs', 'specs', 'sales_visible',
            'shipping', 'shipping_id', 'buy_limit', 'buy_limit_type', 'buy_limit_round', 'buy_limit_max', 'buy_limit_min', 'stock_reduce', 'sku',
            'stock_visible', 'is_hot', 'is_recommend', 'is_new', 'view_num', 'star_num', 'collect_num', 'comment_num', 'transmit_num', 'recommends',
            'sale_start_time', 'sale_end_time', 'sale_status', 'content', 'imgs', 'seo_title', 'seo_keyword', 'seo_desc', 'valuation'
        ],
        // 编辑
        'edit' => [
            'cate_cat', 'brand_id', 'virtual', 'name', 'marque', 'barcode', 'remark', 'thumb', 'sort', 'unit', 'attrs', 'specs', 'sales_visible',
            'shipping', 'shipping_id', 'buy_limit', 'buy_limit_type', 'buy_limit_round', 'buy_limit_max', 'buy_limit_min', 'stock_reduce', 'sku',
            'stock_visible', 'is_hot', 'is_recommend', 'is_new', 'view_num', 'star_num', 'collect_num', 'comment_num', 'transmit_num', 'recommends',
            'sale_start_time', 'sale_end_time', 'sale_status', 'idx', 'content', 'imgs', 'seo_title', 'seo_keyword', 'seo_desc', 'valuation'
        ],
        // 保存详情
        'detail' => ['idx', 'content', 'imgs', 'attrs', 'specs', 'seo_title', 'seo_keyword', 'seo_desc', 'recommends'],
        // 产品信息
        'product' => ['sku', 'img', 'price', 'cost_price', 'market_price', 'stock', 'code', 'weight', 'volume'],
        // 编辑产品信息
        'edit_product' => ['idx', 'img', 'price', 'cost_price', 'market_price', 'stock', 'code', 'weight', 'volume'],

        // 审核
        'audit' => ['idx', 'status'],
        // 修改销售状态
        'toggle' => ['idx', 'sale_status'],
        // 营销信息
        'marketing' => [
            'idx', 'cate_cat', 'name', 'thumb', 'valuation', 'sort', 'shipping', 'shipping_id',
            'is_hot', 'is_new', 'is_recommend', 'sale_start_time', 'sale_end_time'
        ]
    ];

    /**
     * 产品类型合法值
     *
     * @param string $value
     * @return boolean
     */
    public function virtual($value): bool
    {
        return isset(GoodsEmun::VIRTUAL_TYPE_TITLE[$value]);
    }

    /**
     * 发货方式合法值
     *
     * @param string $value
     * @return boolean
     */
    public function shipping($value): bool
    {
        return isset(GoodsEmun::SHIPPING_STATUS_TITLE[$value]);
    }

    /**
     * 是否限购合法值
     *
     * @param string $value
     * @return boolean
     */
    public function buyLimit($value): bool
    {
        return isset(GoodsEmun::BUY_LIMIT_STATUS_TITLE[$value]);
    }

    /**
     * 限购方式合法值
     *
     * @param string $value
     * @return boolean
     */
    public function buyLimitType($value): bool
    {
        return isset(GoodsEmun::BUY_LIMIT_TYPE_TITLE[$value]);
    }

    /**
     * 是否扣减库存合法值
     *
     * @param string $value
     * @return boolean
     */
    public function stockReduce($value): bool
    {
        return isset(GoodsEmun::STOCK_REDUCE_TYPE_TITLE[$value]);
    }

    /**
     * 是否显示库存合法值
     *
     * @param string $value
     * @return boolean
     */
    public function stockVisible($value): bool
    {
        return isset(GoodsEmun::STOCK_VISIBLE_TYPE_TITLE[$value]);
    }

    /**
     * 是否显示销量合法值
     *
     * @param string $value
     * @return boolean
     */
    public function salesVisible($value): bool
    {
        return isset(GoodsEmun::SALE_VISIBLE_TYPE_TITLE[$value]);
    }

    /**
     * 是否热门合法值
     *
     * @param string $value
     * @return boolean
     */
    public function isHot($value): bool
    {
        return isset(GoodsEmun::HOT_STATUS_TITLE[$value]);
    }

    /**
     * 是否推荐合法值
     *
     * @param string $value
     * @return boolean
     */
    public function isRecommend($value): bool
    {
        return isset(GoodsEmun::RECOMMEND_STATUS_TITLE[$value]);
    }

    /**
     * 是否新品合法值
     *
     * @param string $value
     * @return boolean
     */
    public function isNew($value): bool
    {
        return isset(GoodsEmun::NEW_STATUS_TITLE[$value]);
    }

    /**
     * 是否开具发票合法值
     *
     * @param string $value
     * @return boolean
     */
    public function isBill($value): bool
    {
        return isset(GoodsEmun::BILL_STATUS_TITLE[$value]);
    }

    /**
     * 状态合法值
     *
     * @param string $value
     * @return boolean
     */
    public function status($value): bool
    {
        return isset(GoodsEmun::GOODS_STATUS_TITLE[$value]);
    }

    /**
     * 销售状态合法值
     *
     * @param string $value
     * @return boolean
     */
    public function saleStatus($value): bool
    {
        return isset(GoodsEmun::GOODS_SALE_STATUS_TITLE[$value]);
    }
}
