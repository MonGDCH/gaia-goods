<?php

declare(strict_types=1);

namespace plugins\goods\controller;

use mon\http\Request;
use plugins\goods\dao\CateDao;
use plugins\admin\comm\Controller;
use plugins\goods\contract\GoodsCateEmun;

/**
 * 分类管理控制器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class CateController extends Controller
{
    /**
     * 分类管理
     *
     * @param Request $request  请求实例
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->get('isApi')) {
            $data = CateDao::instance()->order('sort', 'DESC')->all();
            return $this->success('ok', $data);
        }

        return $this->fetch('cate/index', ['uid' => $request->uid]);
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
            $edit = CateDao::instance()->add($option, $request->uid);
            if (!$edit) {
                return $this->error(CateDao::instance()->getError());
            }

            return $this->success('操作成功');
        }
        // 输出规则树select
        $cate = CateDao::instance()->getCateList(0);
        array_unshift($cate, ['id' => 0, 'name' => '无', 'disabled' => false, 'children' => []]);
        return $this->fetch('cate/add', [
            'idx' => $request->get('idx', 0),
            'cate' => json_encode($cate, JSON_UNESCAPED_UNICODE),
            'status' => GoodsCateEmun::CATE_STATUS_TITLE
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
            $edit = CateDao::instance()->edit($option, $request->uid);
            if (!$edit) {
                return $this->error(CateDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 查询规则
        $data = CateDao::instance()->where('id', $id)->get();
        if (!$data) {
            return $this->error('记录不存在');
        }

        $cate = CateDao::instance()->getCateList(intval($id));
        array_unshift($cate, ['id' => 0, 'name' => '无', 'disabled' => false, 'children' => []]);
        return $this->fetch('cate/edit', [
            'data' => $data,
            'cate' => json_encode($cate, JSON_UNESCAPED_UNICODE),
            'status' => GoodsCateEmun::CATE_STATUS_TITLE
        ]);
    }
}
