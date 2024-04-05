<?php

declare(strict_types=1);

namespace plugins\goods\controller;

use mon\http\Request;
use support\http\Controller;
use plugins\goods\dao\AttrDao;
use plugins\goods\contract\GoodsModelsEmun;

/**
 * 属性管理控制器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class AttrController extends Controller
{
    /**
     * 属性管理
     *
     * @param Request $request  请求实例
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->get('isApi')) {
            $option = $request->get();
            $result = AttrDao::instance()->getList($option);
            return $this->success('ok', $result['list'], ['count' => $result['count']]);
        }

        return $this->fetch('attr/index', [
            'uid' => $request->uid,
            'status' => GoodsModelsEmun::ATTR_STATUS_TITLE
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
            $edit = AttrDao::instance()->add($option, $request->uid);
            if (!$edit) {
                return $this->error(AttrDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        return $this->fetch('attr/add', [
            'typeList' => GoodsModelsEmun::ATTR_TYPE_TITLE,
            'status' => GoodsModelsEmun::ATTR_STATUS_TITLE
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
            $edit = AttrDao::instance()->edit($option, $request->uid);
            if (!$edit) {
                return $this->error(AttrDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 查询规则
        $data = AttrDao::instance()->where('id', $id)->get();
        if (!$data) {
            return $this->error('记录不存在');
        }

        return $this->fetch('attr/edit', [
            'data' => $data,
            'typeList' => GoodsModelsEmun::ATTR_TYPE_TITLE,
            'status' => GoodsModelsEmun::ATTR_STATUS_TITLE
        ]);
    }
}
