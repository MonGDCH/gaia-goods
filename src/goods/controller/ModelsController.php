<?php

declare(strict_types=1);

namespace plugins\goods\controller;

use mon\http\Request;
use support\http\Controller;
use plugins\goods\dao\SpecDao;
use plugins\goods\dao\AttrDao;
use plugins\goods\dao\ModelsDao;
use plugins\goods\contract\GoodsModelsEmun;

/**
 * 模型管理控制器
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class ModelsController extends Controller
{
    /**
     * 获取模型信息
     *
     * @param Request $request
     * @return mixed
     */
    public function getInfo(Request $request)
    {
        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('params faild');
        }

        $info = ModelsDao::instance()->where('id', $id)->get();
        if (!$info) {
            return $this->error('产品模型不存在');
        }

        $attr = [];
        if ($info['attr']) {
            $attr = AttrDao::instance()->field(['type', 'name AS title', 'content AS value'])
                ->whereIn('id', explode(',', $info['attr']))->orderRaw("FIELD(id, {$info['attr']})")->all();
        }

        $spec = [];
        if ($info['spec']) {
            $spec = SpecDao::instance()->field(['name AS title', 'content AS value'])
                ->whereIn('id', explode(',', $info['spec']))->orderRaw("FIELD(id, {$info['spec']})")->all();
        }

        $info['attr_info'] = $attr;
        $info['spec_info'] = $spec;
        return $this->success('ok', $info);
    }

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
            $result = ModelsDao::instance()->getList($option);
            return $this->success('ok', $result['list'], ['count' => $result['count']]);
        }

        return $this->fetch('models/index', [
            'uid' => $request->uid,
            'status' => GoodsModelsEmun::MODELS_STATUS_TITLE
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
            $edit = ModelsDao::instance()->add($option, $request->uid);
            if (!$edit) {
                return $this->error(ModelsDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        return $this->fetch('models/add', ['status' => GoodsModelsEmun::MODELS_STATUS_TITLE]);
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
            $edit = ModelsDao::instance()->edit($option, $request->uid);
            if (!$edit) {
                return $this->error(ModelsDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        $id = $request->get('idx');
        if (!check('id', $id)) {
            return $this->error('参数错误');
        }
        // 查询规则
        $data = ModelsDao::instance()->where('id', $id)->get();
        if (!$data) {
            return $this->error('记录不存在');
        }
        $this->assign('data', $data);
        $this->assign('status', GoodsModelsEmun::MODELS_STATUS_TITLE);
        return $this->fetch('models/edit');
    }

    /**
     * 绑定模型数据
     *
     * @param Request $request
     * @return void
     */
    public function bind(Request $request)
    {
        // post更新操作
        if ($request->isPost()) {
            $option = $request->post();
            $edit = ModelsDao::instance()->bindModels($option, $request->uid);
            if (!$edit) {
                return $this->error(ModelsDao::instance()->getError());
            }

            return $this->success('操作成功');
        }

        // 模型信息
        $id = $request->get('idx', 0);
        $bind = $request->get('bind');
        $info = ModelsDao::instance()->where('id', $id)->get();
        if (!$info) {
            return $this->error('模型不存在');
        }
        switch ($bind) {
            case 'spec':
                // 规格
                $list = SpecDao::instance()->field(['id', 'name', 'remark'])->all();
                $select = explode(',', $info['spec']);
                break;
            case 'attr':
                // 属性
                $list = AttrDao::instance()->field(['id', 'name', 'remark'])->all();
                $select = explode(',', $info['attr']);
                break;
            default:
                return $this->error('模型绑定参数不支持');
        }

        $this->assign('idx', $id);
        $this->assign('bind', $bind);
        $this->assign('data', $info);
        $this->assign('list', json_encode($list, JSON_UNESCAPED_UNICODE));
        $this->assign('select', json_encode($select, JSON_UNESCAPED_UNICODE));
        return $this->fetch('models/bind');
    }
}
