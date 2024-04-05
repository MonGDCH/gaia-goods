<?php

declare(strict_types=1);

namespace plugins\goods\controller;

use mon\http\Request;
use plugins\goods\dao\CateDao;
use plugins\goods\dao\GoodsDao;
use plugins\goods\dao\BrandDao;
use plugins\goods\dao\ModelsDao;
use plugins\goods\dao\ProductDao;
use plugins\admin\comm\Controller;
use plugins\goods\dao\ShippingTmpDao;
use plugins\goods\contract\GoodsEmun;
use plugins\goods\contract\GoodsBrandEmun;
use plugins\goods\contract\GoodsModelsEmun;
use plugins\goods\contract\ShippingTmpEmun;

/**
 * 产品管理控制器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class ProductController extends Controller
{
    /**
     * 产品管理
     *
     * @param Request $request  请求实例
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->get('isApi')) {
            $data = GoodsDao::instance()->getList($request->get(null, []));
            return $this->success('ok', $data['list'], ['count' => $data['count']]);
        }

        $cateList = CateDao::instance()->getCateList(0);
        array_unshift($cateList, ['id' => '', 'name' => '全部', 'disabled' => false, 'children' => []]);
        return $this->fetch('goods/index', [
            'uid' => $request->uid,
            'secneList' => GoodsEmun::GOODS_SECNE_TYPE,
            'cateList' => json_encode($cateList, JSON_UNESCAPED_UNICODE),
            'statusList' => json_encode(GoodsEmun::GOODS_STATUS, JSON_UNESCAPED_UNICODE),
            'saleStatusList' => json_encode(GoodsEmun::GOODS_SALE_STATUS, JSON_UNESCAPED_UNICODE),
        ]);
    }

    /**
     * 新增
     *
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $option = $request->post();
            $option['content'] = $request->post('content', null, false);
            $option['attrs'] = $request->post('attrs', null, false);
            $option['specs'] = $request->post('specs', null, false);
            $option['sku'] = $request->post('sku', null, false);
            // 默认未上架
            $option['sale_status'] = GoodsEmun::GOODS_SALE_STATUS['not_sale'];
            $edit = GoodsDao::instance()->add($option, $request->uid);
            if (!$edit) {
                return $this->error(GoodsDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        // 扣减库存 
        $this->assign('stockReduce', GoodsEmun::STOCK_REDUCE_TYPE);
        // 显示库存 
        $this->assign('stockVisible', GoodsEmun::STOCK_VISIBLE_TYPE);
        // 显示销量 
        $this->assign('salesVisible', GoodsEmun::SALE_VISIBLE_TYPE);
        // 是否热门
        $this->assign('isHot', GoodsEmun::HOT_STATUS);
        // 显示销量 
        $this->assign('isNew', GoodsEmun::NEW_STATUS);
        // 显示销量 
        $this->assign('isRecommend', GoodsEmun::RECOMMEND_STATUS);
        // 产品类型
        $this->assign('virtualType', GoodsEmun::VIRTUAL_TYPE_TITLE);
        // 购买限制
        $this->assign('buyLimit', GoodsEmun::BUY_LIMIT_STATUS);
        $this->assign('buyLimitType', GoodsEmun::BUY_LIMIT_TYPE_TITLE);
        // 产品分类
        $cateList = CateDao::instance()->getCateList(0);
        $this->assign('cateList', json_encode($cateList, JSON_UNESCAPED_UNICODE));
        // 产品品牌
        $brandList = BrandDao::instance()->where('status', GoodsBrandEmun::BRAND_STATUS['enable'])->field(['id', 'name AS title'])->all();
        array_unshift($brandList, ['id' => 0, 'title' => '无']);
        $this->assign('brandList', $brandList);
        // 产品模型
        $moduleList = ModelsDao::instance()->where('status', GoodsModelsEmun::MODELS_STATUS['enable'])->field(['id AS value', 'name', 'remark'])->order('id', 'DESC')->all();
        $this->assign('moduleList', json_encode($moduleList, JSON_UNESCAPED_UNICODE));
        // 配送模板
        $shippingList = ShippingTmpDao::instance()->where('status', ShippingTmpEmun::TMP_STATUS['enable'])->field(['id', 'name AS title'])->order('sort', 'DESC')->all();
        array_unshift($shippingList, ['id' => 0, 'title' => '无']);
        $this->assign('shippingList', $shippingList);
        $this->assign('shippingType', GoodsEmun::SHIPPING_STATUS_TITLE);

        // 复制产品
        $copy = $request->get('copy');
        if (check('id', $copy)) {
            // 产品信息
            $data = GoodsDao::instance()->getInfo(['id' => $copy]);
            if (!$data) {
                return $this->error(GoodsDao::instance()->getError());
            }
            $data['view_num'] = 0;
            $data['star_num'] = 0;
            $data['collect_num'] = 0;
            $data['transmit_num'] = 0;
            $data['comment_num'] = 0;
            $this->assign('title', '复制产品 - ' . $data['name']);
            $this->assign('data', $data);
            return $this->fetch('goods/edit');
        }

        return $this->fetch('goods/add');
    }

    /**
     * 编辑
     *
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request)
    {
        // post更新操作
        if ($request->isPost()) {
            $option = $request->post();
            $option['content'] = $request->post('content', null, false);
            $option['attrs'] = $request->post('attrs', null, false);
            $option['specs'] = $request->post('specs', null, false);
            $option['sku'] = $request->post('sku', null, false);
            // 默认未上架
            $option['sale_status'] = GoodsEmun::GOODS_SALE_STATUS['not_sale'];
            // 编辑需要重新出库
            $option['status'] = GoodsEmun::GOODS_STATUS['audit'];
            $edit = GoodsDao::instance()->edit($option, $request->uid);
            if (!$edit) {
                return $this->error(GoodsDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 产品信息
        $data = GoodsDao::instance()->getInfo(['id' => $id]);
        if (!$data) {
            return $this->error(GoodsDao::instance()->getError());
        }
        $this->assign('data', $data);
        // 扣减库存 
        $this->assign('stockReduce', GoodsEmun::STOCK_REDUCE_TYPE);
        // 显示库存 
        $this->assign('stockVisible', GoodsEmun::STOCK_VISIBLE_TYPE);
        // 显示销量 
        $this->assign('salesVisible', GoodsEmun::SALE_VISIBLE_TYPE);
        // 是否热门
        $this->assign('isHot', GoodsEmun::HOT_STATUS);
        // 显示销量 
        $this->assign('isNew', GoodsEmun::NEW_STATUS);
        // 显示销量 
        $this->assign('isRecommend', GoodsEmun::RECOMMEND_STATUS);
        // 产品类型
        $this->assign('virtualType', GoodsEmun::VIRTUAL_TYPE_TITLE);
        // 购买限制
        $this->assign('buyLimit', GoodsEmun::BUY_LIMIT_STATUS);
        $this->assign('buyLimitType', GoodsEmun::BUY_LIMIT_TYPE_TITLE);
        // 产品分类
        $cateList = CateDao::instance()->getCateList(0);
        $this->assign('cateList', json_encode($cateList, JSON_UNESCAPED_UNICODE));
        // 产品品牌
        $brandList = BrandDao::instance()->where('status', GoodsBrandEmun::BRAND_STATUS['enable'])->field(['id', 'name AS title'])->all();
        array_unshift($brandList, ['id' => 0, 'title' => '无']);
        $this->assign('brandList', $brandList);
        // 产品模型
        $moduleList = ModelsDao::instance()->where('status', GoodsModelsEmun::MODELS_STATUS['enable'])->field(['id AS value', 'name', 'remark'])->order('id', 'DESC')->all();
        $this->assign('moduleList', json_encode($moduleList, JSON_UNESCAPED_UNICODE));
        // 配送模板
        $shippingList = ShippingTmpDao::instance()->where('status', ShippingTmpEmun::TMP_STATUS['enable'])->field(['id', 'name AS title'])->order('sort', 'DESC')->all();
        array_unshift($shippingList, ['id' => 0, 'title' => '无']);
        $this->assign('shippingList', $shippingList);
        $this->assign('shippingType', GoodsEmun::SHIPPING_STATUS_TITLE);

        $this->assign('title', '编辑产品');
        return $this->fetch('goods/edit');
    }

    /**
     * 预览
     *
     * @param Request $request
     * @return mixed
     */
    public function preview(Request $request)
    {
        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 产品信息
        $data = GoodsDao::instance()->getInfo(['id' => $id]);
        if (!$data) {
            return $this->error(GoodsDao::instance()->getError());
        }

        // 产品分类
        // $data['cate_txt'] = implode(', ', $data['cate_list']);
        // 产品类型
        $data['virtual_txt'] = GoodsEmun::VIRTUAL_TYPE_TITLE[$data['virtual']];
        // 产品品牌
        $data['brand_txt'] = '无';
        $brandInfo = BrandDao::instance()->where(['id' => $data['brand_id']])->field(['name', 'logo'])->get();
        if ($brandInfo) {
            $data['brand_txt'] = $brandInfo['name'];
        }
        // 产品分类
        $cateList = CateDao::instance()->getCateList(0, 'children', ['id', 'pid', 'name', '1 AS disabled']);
        $this->assign('cateList', json_encode($cateList, JSON_UNESCAPED_UNICODE));
        // 产品轮播图
        $data['covers_list'] = $data['covers'] ? explode(',', $data['covers']) : [];
        // 限购类型
        $data['buy_limit_txt'] = GoodsEmun::BUY_LIMIT_TYPE_TITLE[$data['buy_limit_type']];
        // 配送模板
        $data['shipping_tmp'] = '';
        if ($data['shipping'] == GoodsEmun::SHIPPING_STATUS['enable'] && $data['shipping_id'] != '0') {
            $shipping = ShippingTmpDao::instance()->where('id', $data['shipping_id'])->field(['name'])->get();
            if ($shipping) {
                $data['shipping_tmp'] = $shipping['name'];
            }
        }

        $this->assign('data', $data);
        return $this->fetch('goods/preview');
    }

    /**
     * 产品出库
     *
     * @param Request $request
     * @return mixed
     */
    public function audit(Request $request)
    {
        // post审核操作
        if ($request->isPost()) {
            $option = $request->post();
            $edit = GoodsDao::instance()->audit($option, $request->uid);
            if (!$edit) {
                return $this->error(GoodsDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        // 审核和预览一样，只是多了审核操作
        $this->assign('audit', true);
        return $this->preview($request);
    }

    /**
     * 产品上下架
     *
     * @param Request $request
     * @return mixed
     */
    public function saleStatus(Request $request)
    {
        $data = $request->post();
        $edit = GoodsDao::instance()->toggle($data, $request->uid);
        if (!$edit) {
            return $this->error(GoodsDao::instance()->getError());
        }

        return $this->success('操作成功');
    }

    /**
     * 运营管理
     *
     * @param Request $request
     * @return mixed
     */
    public function marketing(Request $request)
    {
        // post修改操作
        if ($request->isPost()) {
            $option = $request->post();
            $edit = GoodsDao::instance()->marketing($option, $request->uid);
            if (!$edit) {
                return $this->error(GoodsDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 产品信息
        $data = GoodsDao::instance()->where('id', $id)->get();
        if (!$data) {
            return $this->error('产品不存在');
        }
        // 产品分类
        $cateList = CateDao::instance()->getCateList(0);
        $this->assign('cateList', json_encode($cateList, JSON_UNESCAPED_UNICODE));
        $this->assign('data', $data);
        return $this->fetch('goods/marketing');
    }

    /**
     * 库存管理
     *
     * @param Request $request
     * @return mixed
     */
    public function inventory(Request $request)
    {
        // post修改操作
        if ($request->isPost()) {
            $option = $request->post();
            // 修改单行记录
            if (isset($option['signLine']) && $option['signLine'] == 1) {
                $edit = ProductDao::instance()->edit($option, $request->uid);
                if (!$edit) {
                    return $this->error(ProductDao::instance()->getError());
                }
            } else {
                // 批量修改
                $data = json_decode($request->post('data', null, false), true);
                $save = ProductDao::instance()->batchEdit($data, $request->uid);
                if (!$save) {
                    return $this->error(ProductDao::instance()->getError());
                }
            }

            return $this->success('操作成功');
        }

        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 产品信息
        $data = GoodsDao::instance()->getInfo(['id' => $id]);
        if (!$data) {
            return $this->error(GoodsDao::instance()->getError());
        }

        $this->assign('data', $data);
        return $this->fetch('goods/inventory');
    }
}
