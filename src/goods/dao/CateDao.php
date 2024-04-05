<?php

declare(strict_types=1);

namespace plugins\goods\dao;

use Throwable;
use mon\util\Tree;
use mon\log\Logger;
use mon\thinkOrm\Dao;
use mon\util\Instance;
use app\admin\dao\AdminLogDao;
use plugins\goods\validate\CateValidate;
use plugins\goods\contract\GoodsCateEmun;

/**
 * 
 * 产品分类Dao操作
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class CateDao extends Dao
{
    use Instance;

    /**
     * 操作表
     *
     * @var string
     */
    protected $table = 'goods_cate';

    /**
     * 自动写入时间戳
     *
     * @var boolean
     */
    protected $autoWriteTimestamp = true;

    /**
     * 验证器
     *
     * @var string
     */
    protected $validate = CateValidate::class;

    /**
     * 新增
     *
     * @param array $data
     * @param integer $adminID
     * @return integer
     */
    public function add(array $data, int $adminID): int
    {
        $check = $this->validate()->data($data)->scope('add')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return 0;
        }

        if ($data['pid'] != 0) {
            $parentInfo = $this->where('id', $data['pid'])->get();
            if (!$parentInfo) {
                $this->error = '父级分类不存在';
                return 0;
            }
        }

        $this->startTrans();
        try {
            Logger::instance()->channel()->info('Add goods cate');
            $field = ['pid', 'name', 'status', 'sort', 'img', 'remark'];
            $cate_id = $this->allowField($field)->save($data, true, true);
            if (!$cate_id) {
                $this->rollback();
                $this->error = '新增产品分类失败';
                return 0;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '新增产品分类',
                    'content' => '新增产品分类：' . $data['name'],
                    'sid' => $cate_id
                ]);
                if (!$record) {
                    $this->rollback();
                    $this->error = '记录操作日志失败,' . AdminLogDao::instance()->getError();;
                    return 0;
                }
            }

            $this->commit();
            return $cate_id;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '新增产品分类异常';
            Logger::instance()->channel()->error('Add goods cate exception, msg => ' . $e->getMessage(), ['trace' => true]);
            return 0;
        }
    }

    /**
     * 编辑
     *
     * @param array $data
     * @param integer $adminID
     * @return boolean
     */
    public function edit(array $data, int $adminID): bool
    {
        $check = $this->validate()->data($data)->scope('edit')->check();
        if (!$check) {
            $this->error = $this->validate()->getError();
            return false;
        }

        if ($data['pid'] == $data['idx']) {
            $this->error = '父级不能为自身';
            return false;
        }

        $info = $this->where('id', $data['idx'])->get();
        if (!$info) {
            $this->error = '产品分类不存在';
            return false;
        }

        $this->startTrans();
        try {
            Logger::instance()->channel()->info('Edit goods cate info');
            $field = ['pid', 'name', 'status', 'sort', 'img', 'remark'];
            $save = $this->allowField($field)->where('id', $info['id'])->save($data);
            if (!$save) {
                $this->rollback();
                $this->error = '修改产品分类失败';
                return false;
            }

            // 记录操作日志
            if ($adminID > 0) {
                $record = AdminLogDao::instance()->record([
                    'uid' => $adminID,
                    'module' => 'goods',
                    'action' => '编辑产品分类',
                    'content' => '编辑产品分类：' . $data['name'] . ', ID：' . $data['idx'],
                    'sid' => $data['idx']
                ]);
                if (!$record) {
                    $this->rollback();
                    $this->error = '记录操作日志失败,' . AdminLogDao::instance()->getError();;
                    return false;
                }
            }

            $this->commit();
            return true;
        } catch (Throwable $e) {
            $this->rollback();
            $this->error = '编辑产品分类异常';
            Logger::instance()->channel()->error('Edit goods cate exception, msg => ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 查询列表
     *
     * @param array $data 请求参数
     * @return array
     */
    public function getList(array $data): array
    {
        $limit = isset($data['limit']) ? intval($data['limit']) : 10;
        $page = isset($data['page']) && is_numeric($data['page']) ? intval($data['page']) : 1;

        // 查询
        $list = $this->scope('list', $data)->page($page, $limit)->all();
        $total = $this->scope('list', $data)->count();

        return [
            'list'      => $list,
            'count'     => $total,
            'pageSize'  => $limit,
            'page'      => $page
        ];
    }

    /**
     * 查询列表场景
     *
     * @param [\mon\thinkOrm\extend\Query $query
     * @param array $option
     * @return mixed
     */
    protected function scopeList($query, array $option)
    {
        // 父级分类搜索
        if (isset($option['pid']) && $this->validate()->int($option['pid'])) {
            $query->where('pid', intval($option['pid']));
        }
        // status搜索
        if (isset($option['status']) && $this->validate()->int($option['status'])) {
            $query->where('status', intval($option['status']));
        }
        // 按名
        if (isset($option['name']) && is_string($option['name']) && !empty($option['name'])) {
            $query->whereLike('name', trim($option['name']) . '%');
        }
        // 时间搜索
        if (isset($option['start_time']) && $this->validate()->int($option['start_time'])) {
            $query->where('update_time', '>=', intval($option['start_time']));
        }
        if (isset($option['end_time']) && $this->validate()->int($option['end_time'])) {
            $query->where('update_time', '<=', intval($option['end_time']));
        }

        // 排序字段，默认 sort
        $order = 'sort';
        if (isset($option['order']) && in_array($option['order'], ['id', 'sort', 'update_time', 'create_time'])) {
            $order = $option['order'];
        }
        // 排序类型，默认 DESC
        $sort = 'DESC';
        if (isset($option['sort']) && in_array(strtoupper($option['sort']), ['ASC', 'DESC'])) {
            $sort = strtoupper($option['sort']);
        }

        return $query->order($order, $sort);
    }

    /**
     * 获取允许编辑分类
     *
     * @param integer $id
     * @param string $childrenName
     * @return array
     */
    public function getCateList(int $id, string $childrenName = 'children', array $field = []): array
    {
        $field = $field ?: ['id', 'pid', 'name', 'IF(id = ' . intval($id) . ', 1, 0) AS disabled'];
        $data = $this->where('status', GoodsCateEmun::CATE_STATUS['enable'])->field($field)->order('sort', 'DESC')->all();
        $dataArr = Tree::instance()->data($data)->getTree($childrenName);
        return $this->formatCateList($dataArr);
    }

    /**
     * 整理获取编辑允许权限菜单数据
     *
     * @param array $data
     * @param boolean $disable
     * @param string $childrenName
     * @return array
     */
    protected function formatCateList(array $data, bool $disable = false, string $childrenName = 'children'): array
    {
        foreach ($data as &$item) {
            $item['disabled'] = ($item['disabled'] == 1 || $disable);
            if (!empty($item[$childrenName])) {
                $item[$childrenName] = $this->formatCateList($item[$childrenName], $item['disabled'], $childrenName);
            }
        }

        return $data;
    }
}
