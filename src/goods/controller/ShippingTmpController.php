<?php

declare(strict_types=1);

namespace plugins\goods\controller;

use mon\http\Request;
use support\http\Controller;
use plugins\goods\dao\ShippingTmpDao;
use plugins\goods\contract\ShippingTmpEmun;

/**
 * 规格管理控制器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class ShippingTmpController extends Controller
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
            $result = ShippingTmpDao::instance()->getList($option);
            return $this->success('ok', $result['list'], ['count' => $result['count']]);
        }

        return $this->fetch('shipping/index', [
            'uid' => $request->uid,
            'status' => ShippingTmpEmun::TMP_STATUS_TITLE,
            'typeList' => json_encode(ShippingTmpEmun::TMP_TYPE_TITLE, JSON_UNESCAPED_UNICODE),
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
            $edit = ShippingTmpDao::instance()->add($option, $request->uid);
            if (!$edit) {
                return $this->error(ShippingTmpDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        return $this->fetch('shipping/add', [
            'status' => ShippingTmpEmun::TMP_STATUS_TITLE,
            'typeList' => ShippingTmpEmun::TMP_TYPE_TITLE
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
            $edit = ShippingTmpDao::instance()->edit($option, $request->uid);
            if (!$edit) {
                return $this->error(ShippingTmpDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 查询规则
        $data = ShippingTmpDao::instance()->where('id', $id)->get();
        if (!$data) {
            return $this->error('记录不存在');
        }

        return $this->fetch('shipping/edit', [
            'data' => $data,
            'status' => ShippingTmpEmun::TMP_STATUS_TITLE,
            'typeList' => ShippingTmpEmun::TMP_TYPE_TITLE
        ]);
    }
}
