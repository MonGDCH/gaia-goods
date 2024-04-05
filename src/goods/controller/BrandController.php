<?php

declare(strict_types=1);

namespace plugins\goods\controller;

use mon\http\Request;
use plugins\goods\dao\CateDao;
use plugins\goods\dao\BrandDao;
use plugins\admin\comm\Controller;
use plugins\goods\contract\GoodsBrandEmun;

/**
 * 品牌管理控制器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class BrandController extends Controller
{
    /**
     * 查看
     *
     * @param Request $request  请求实例
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->get('isApi')) {
            $option = $request->get();
            $result = BrandDao::instance()->getList($option);
            return $this->success('ok', $result['list'], ['count' => $result['count']]);
        }

        $cate = CateDao::instance()->getCateList(0);
        array_unshift($cate, ['id' => '', 'name' => '全部', 'disabled' => false, 'children' => []]);
        return $this->fetch('brand/index', [
            'uid' => $request->uid,
            'cate' => json_encode($cate, JSON_UNESCAPED_UNICODE),
            'status' => GoodsBrandEmun::BRAND_STATUS_TITLE
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
            $edit = BrandDao::instance()->add($option, $request->uid);
            if (!$edit) {
                return $this->error(BrandDao::instance()->getError());
            }

            return $this->success('操作成功');
        }
        // 输出规则树select
        $cate = CateDao::instance()->getCateList(0);
        array_unshift($cate, ['id' => 0, 'name' => '无', 'disabled' => false, 'children' => []]);
        return $this->fetch('brand/add', [
            'cate' => json_encode($cate, JSON_UNESCAPED_UNICODE),
            'status' => GoodsBrandEmun::BRAND_STATUS_TITLE
        ]);
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
            $edit = BrandDao::instance()->edit($option, $request->uid);
            if (!$edit) {
                return $this->error(BrandDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 查询规则
        $data = BrandDao::instance()->where('id', $id)->get();
        if (!$data) {
            return $this->error('记录不存在');
        }
        $cate = CateDao::instance()->getCateList(0);
        array_unshift($cate, ['id' => 0, 'name' => '无', 'disabled' => false, 'children' => []]);
        return $this->fetch('brand/edit', [
            'data' => $data,
            'cate' => json_encode($cate, JSON_UNESCAPED_UNICODE),
            'status' => GoodsBrandEmun::BRAND_STATUS_TITLE
        ]);
    }
}
