<?php

declare(strict_types=1);

namespace plugins\goods\controller;

use mon\http\Request;
use plugins\goods\dao\SpecDao;
use plugins\admin\comm\Controller;
use plugins\goods\contract\GoodsModelsEmun;

/**
 * 规格管理控制器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class SpecController extends Controller
{
    /**
     * 规格管理
     *
     * @param Request $request  请求实例
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->get('isApi')) {
            $option = $request->get();
            $result = SpecDao::instance()->getList($option);
            return $this->success('ok', $result['list'], ['count' => $result['count']]);
        }

        return $this->fetch('spec/index', [
            'uid' => $request->uid,
            'status' => GoodsModelsEmun::SPEC_STATUS_TITLE
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
            $edit = SpecDao::instance()->add($option, $request->uid);
            if (!$edit) {
                return $this->error(SpecDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        return $this->fetch('spec/add', ['status' => GoodsModelsEmun::SPEC_STATUS_TITLE]);
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
            $edit = SpecDao::instance()->edit($option, $request->uid);
            if (!$edit) {
                return $this->error(SpecDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 查询规则
        $data = SpecDao::instance()->where('id', $id)->get();
        if (!$data) {
            return $this->error('记录不存在');
        }

        return $this->fetch('spec/edit', [
            'data' => $data,
            'status' => GoodsModelsEmun::SPEC_STATUS_TITLE
        ]);
    }
}
